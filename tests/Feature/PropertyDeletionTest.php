<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PropertyDeletionTest extends TestCase
{
    use RefreshDatabase;

    protected $owner;
    protected $anotherOwner;
    protected $seeker;
    protected $property;
    protected $mainImagePath;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');

        // Create Users
        $this->owner = User::create([
            'name' => 'Owner A',
            'email' => 'owner.a@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->anotherOwner = User::create([
            'name' => 'Owner B',
            'email' => 'owner.b@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->seeker = User::create([
            'name' => 'Seeker User',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        // Put a fake image in storage
        $this->mainImagePath = 'properties/fake_main.png';
        Storage::disk('public')->put($this->mainImagePath, 'fake content');

        // Create Property
        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Lestari Indah',
            'slug' => 'kos-lestari-indah',
            'type' => 'Campur',
            'area' => 'Sekarbela',
            'address' => 'Jln. Lestari No. 10, Mataram',
            'latitude' => '-8.5900',
            'longitude' => '116.0900',
            'description' => 'Kos nyaman dekat kampus',
            'main_image' => $this->mainImagePath,
            'is_verified' => true,
            'status' => 'published',
        ]);
    }

    public function test_owner_can_soft_delete_own_property_and_images_are_preserved(): void
    {
        // Act as Owner A
        $response = $this->actingAs($this->owner)->delete(route('property.destroy', $this->property));

        // Assert redirect
        $response->assertRedirect(route('dashboard.owner'));
        $response->assertSessionHas('success', 'Properti berhasil diarsipkan.');

        // Assert property is soft deleted in database
        $this->assertSoftDeleted('properties', [
            'id' => $this->property->id,
        ]);

        // Assert that the image file STILL exists in storage (not deleted)
        Storage::disk('public')->assertExists($this->mainImagePath);
    }

    public function test_other_owner_cannot_delete_property(): void
    {
        // Act as Owner B (not the owner of this property)
        $response = $this->actingAs($this->anotherOwner)->delete(route('property.destroy', $this->property));

        $response->assertStatus(403);

        // Assert property is NOT soft deleted in database
        $this->assertDatabaseHas('properties', [
            'id' => $this->property->id,
            'deleted_at' => null,
        ]);

        // Image file should also still exist
        Storage::disk('public')->assertExists($this->mainImagePath);
    }

    public function test_seeker_cannot_delete_property(): void
    {
        // Act as Seeker
        $response = $this->actingAs($this->seeker)->delete(route('property.destroy', $this->property));

        $response->assertStatus(403);

        // Assert property is NOT soft deleted
        $this->assertDatabaseHas('properties', [
            'id' => $this->property->id,
            'deleted_at' => null,
        ]);
    }

    public function test_guest_cannot_delete_property(): void
    {
        // Act as Guest (Unauthenticated)
        $response = $this->delete(route('property.destroy', $this->property));

        $response->assertRedirect('/login');

        // Assert property is NOT soft deleted
        $this->assertDatabaseHas('properties', [
            'id' => $this->property->id,
            'deleted_at' => null,
        ]);
    }

    public function test_property_creation_fails_if_available_rooms_exceeds_total_rooms(): void
    {
        $this->actingAs($this->owner);

        $payload = [
            'name' => 'Kos Baru',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jl. Baru No. 1',
            'room_name' => 'Kamar Standard',
            'price_per_month' => 500000,
            'total_rooms' => 5,
            'available_rooms' => 6,
        ];

        $response = $this->post(route('property.store'), $payload);
        $response->assertSessionHasErrors(['available_rooms']);
    }

    public function test_property_update_fails_if_available_rooms_exceeds_total_rooms(): void
    {
        $this->actingAs($this->owner);

        $payload = [
            'name' => 'Kos Lestari Indah',
            'type' => 'Campur',
            'area' => 'Sekarbela',
            'address' => 'Jln. Lestari No. 10, Mataram',
            'room_name' => 'Kamar Deluxe AC',
            'price_per_month' => 1500000,
            'total_rooms' => 5,
            'available_rooms' => 6,
        ];

        $response = $this->put(route('property.update', $this->property), $payload);
        $response->assertSessionHasErrors(['available_rooms']);
    }
}
