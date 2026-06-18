<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed default settings just in case, but migration does it.
        Setting::setValue('admin_fee', 2500);
        Setting::setValue('commission_rate', 5);
    }

    public function test_guest_cannot_access_admin_dashboard(): void
    {
        $response = $this->get('/dashboard-admin');
        $response->assertRedirect('/login');
    }

    public function test_seeker_cannot_access_admin_dashboard(): void
    {
        $seeker = User::create([
            'name' => 'Seeker Test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);
        $response = $this->actingAs($seeker)->get('/dashboard-admin');
        $response->assertStatus(403);
    }

    public function test_owner_cannot_access_admin_dashboard(): void
    {
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $response = $this->actingAs($owner)->get('/dashboard-admin');
        $response->assertStatus(403);
    }

    public function test_admin_can_access_admin_dashboard(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $response = $this->actingAs($admin)->get('/dashboard-admin');
        $response->assertStatus(200);
        $response->assertSee('Portal Admin');
    }

    public function test_admin_can_verify_seeker(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $seeker = User::create([
            'name' => 'Seeker Test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'identity_photo' => 'identities/ktp.jpg',
            'selfie_photo' => 'identities/selfie.jpg',
            'is_verified' => false
        ]);

        $response = $this->actingAs($admin)->post(route('admin.verify-seeker', $seeker));
        $response->assertRedirect();
        
        $seeker->refresh();
        $this->assertTrue($seeker->is_verified);
    }

    public function test_admin_can_approve_property(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Kebon UNRAM',
            'slug' => 'kos-kebon-unram',
            'type' => 'Campur',
            'area' => 'Selaparang',
            'address' => 'Jln. Kebon No. 4, Mataram',
            'latitude' => '-8.5880',
            'longitude' => '116.0970',
            'description' => 'Kos dekat kampus',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.approve-property', $property));
        $response->assertRedirect();

        $property->refresh();
        $this->assertEquals('published', $property->status);
    }

    public function test_admin_can_update_settings(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.update-settings'), [
            'admin_fee' => 5000,
            'commission_rate' => 10,
        ]);
        $response->assertRedirect();

        $this->assertEquals('5000', Setting::getValue('admin_fee'));
        $this->assertEquals('10', Setting::getValue('commission_rate'));
    }

    public function test_admin_can_reject_property_and_sends_email(): void
    {
        \Illuminate\Support\Facades\Mail::fake();

        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Kebon UNRAM',
            'slug' => 'kos-kebon-unram',
            'type' => 'Campur',
            'area' => 'Selaparang',
            'address' => 'Jln. Kebon No. 4, Mataram',
            'latitude' => '-8.5880',
            'longitude' => '116.0970',
            'description' => 'Kos dekat kampus',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.property.reject', $property), [
            'rejection_reason' => 'Foto properti kurang jelas dan tidak sesuai.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Properti berhasil ditolak dengan alasan.');

        $property->refresh();
        $this->assertEquals('rejected', $property->status);
        $this->assertEquals('Foto properti kurang jelas dan tidak sesuai.', $property->rejection_reason);

        \Illuminate\Support\Facades\Mail::assertSent(\App\Mail\ModerationNotificationMail::class, function ($mail) use ($owner, $property) {
            return $mail->hasTo($owner->email) &&
                   $mail->type === 'property_rejected' &&
                   $mail->model->id === $property->id;
        });
    }

    public function test_non_admin_cannot_reject_property(): void
    {
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Kebon UNRAM',
            'slug' => 'kos-kebon-unram',
            'type' => 'Campur',
            'area' => 'Selaparang',
            'address' => 'Jln. Kebon No. 4, Mataram',
            'latitude' => '-8.5880',
            'longitude' => '116.0970',
            'description' => 'Kos dekat kampus',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($owner)->post(route('admin.property.reject', $property), [
            'rejection_reason' => 'Foto properti kurang jelas dan tidak sesuai.',
        ]);

        $response->assertStatus(403);
        $this->assertEquals('draft', $property->fresh()->status);
    }

    public function test_admin_cannot_reject_property_without_reason_or_short_reason(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $owner = User::create([
            'name' => 'Owner Test',
            'email' => 'owner@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);
        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Kebon UNRAM',
            'slug' => 'kos-kebon-unram',
            'type' => 'Campur',
            'area' => 'Selaparang',
            'address' => 'Jln. Kebon No. 4, Mataram',
            'latitude' => '-8.5880',
            'longitude' => '116.0970',
            'description' => 'Kos dekat kampus',
            'main_image' => 'properties/test.png',
            'is_verified' => true,
            'status' => 'draft',
        ]);

        // Test missing reason
        $response = $this->actingAs($admin)->post(route('admin.property.reject', $property), [
            'rejection_reason' => '',
        ]);
        $response->assertSessionHasErrors('rejection_reason');
        $this->assertEquals('draft', $property->fresh()->status);

        // Test too short reason
        $response = $this->actingAs($admin)->post(route('admin.property.reject', $property), [
            'rejection_reason' => 'Oops',
        ]);
        $response->assertSessionHasErrors('rejection_reason');
        $this->assertEquals('draft', $property->fresh()->status);
    }
}
