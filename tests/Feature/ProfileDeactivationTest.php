<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileDeactivationTest extends TestCase
{
    use RefreshDatabase;

    protected $seeker;
    protected $owner;
    protected $property;
    protected $roomType;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seeker = User::create([
            'name' => 'Seeker User',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $this->owner = User::create([
            'name' => 'Owner User',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $this->property = Property::create([
            'user_id' => $this->owner->id,
            'name' => 'Kos Test',
            'slug' => 'kos-test',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jl. Test No. 1',
            'latitude' => '-8.6000',
            'longitude' => '116.1000',
            'status' => 'published',
        ]);

        $this->roomType = RoomType::create([
            'property_id' => $this->property->id,
            'name' => 'Kamar Standard',
            'price_per_month' => 600000,
            'total_rooms' => 5,
            'available_rooms' => 5,
        ]);
    }

    public function test_seeker_without_active_bookings_can_deactivate(): void
    {
        $response = $this->actingAs($this->seeker)->post(route('profile.deactivate'));
        $response->assertRedirect(route('home'));
        $this->assertDatabaseMissing('users', ['id' => $this->seeker->id]);
    }

    public function test_seeker_with_active_booking_cannot_deactivate(): void
    {
        Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 600000,
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        $response = $this->actingAs($this->seeker)->post(route('profile.deactivate'));
        $response->assertSessionHas('error', 'Anda tidak dapat menonaktifkan akun karena memiliki transaksi atau sewa aktif/tertunda.');
        $this->assertDatabaseHas('users', ['id' => $this->seeker->id]);
    }

    public function test_owner_with_active_booking_on_property_cannot_deactivate(): void
    {
        Booking::create([
            'user_id' => $this->seeker->id,
            'room_type_id' => $this->roomType->id,
            'check_in_date' => now()->addDays(2),
            'duration_months' => 1,
            'total_price' => 600000,
            'status' => 'Active',
            'payment_status' => 'Paid',
        ]);

        $response = $this->actingAs($this->owner)->post(route('profile.deactivate'));
        $response->assertSessionHas('error', 'Anda tidak dapat menonaktifkan akun karena kos Anda memiliki transaksi atau sewa aktif/tertunda.');
        $this->assertDatabaseHas('users', ['id' => $this->owner->id]);
    }

    public function test_home_page_redirects_authenticated_roles(): void
    {
        // Seeker redirect
        $responseSeeker = $this->actingAs($this->seeker)->get('/');
        $responseSeeker->assertRedirect(route('dashboard.seeker'));

        // Owner redirect
        $responseOwner = $this->actingAs($this->owner)->get('/');
        $responseOwner->assertRedirect(route('dashboard.owner'));

        // Admin redirect
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $responseAdmin = $this->actingAs($admin)->get('/');
        $responseAdmin->assertRedirect(route('dashboard.admin'));
    }
}
