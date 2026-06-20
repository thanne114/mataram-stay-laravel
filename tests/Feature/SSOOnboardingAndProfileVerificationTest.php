<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Property;
use App\Models\RoomType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class SSOOnboardingAndProfileVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test Google callback without pre-defined role in session redirects to choose-role page.
     */
    public function test_google_callback_without_role_redirects_to_choose_role(): void
    {
        // Mock Google Provider User
        $googleUser = $this->createMock(SocialiteUser::class);
        $googleUser->method('getId')->willReturn('google-id-onboard-999');
        $googleUser->method('getName')->willReturn('SSO Seeker');
        $googleUser->method('getEmail')->willReturn('sso.seeker@example.com');
        $googleUser->method('getAvatar')->willReturn('https://lh3.googleusercontent.com/a/avatar');

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->method('user')->willReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        // Make sure no role exists in session
        session()->forget('google_sso_role');

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect(route('auth.google.choose-role'));
        
        $this->assertEquals([
            'name' => 'SSO Seeker',
            'email' => 'sso.seeker@example.com',
            'google_id' => 'google-id-onboard-999',
            'avatar' => 'https://lh3.googleusercontent.com/a/avatar',
        ], session('google_sso_user'));
        
        $this->assertGuest();
    }

    /**
     * Test choose-role page redirects to login page if no Google user session exists.
     */
    public function test_choose_role_page_requires_sso_session(): void
    {
        $response = $this->get(route('auth.google.choose-role'));

        $response->assertRedirect(route('login'));
        $response->assertSessionHas('error');
    }

    /**
     * Test submitting the choose-role form successfully creates user, logs them in, and redirects.
     */
    public function test_choose_role_saves_role_creates_user_and_authenticates(): void
    {
        session([
            'google_sso_user' => [
                'name' => 'SSO Owner',
                'email' => 'sso.owner@example.com',
                'google_id' => 'google-id-onboard-888',
                'avatar' => 'https://lh3.googleusercontent.com/a/avatar-owner',
            ]
        ]);

        $response = $this->post(route('auth.google.choose-role'), [
            'role' => 'owner'
        ]);

        $response->assertRedirect(route('dashboard.owner'));
        
        $this->assertDatabaseHas('users', [
            'email' => 'sso.owner@example.com',
            'google_id' => 'google-id-onboard-888',
            'role' => 'owner',
        ]);

        $this->assertAuthenticated();
        
        $user = User::where('email', 'sso.owner@example.com')->first();
        $this->assertNotNull($user->username);
        $this->assertNull($user->password);
    }

    /**
     * Test that the WhatsApp configuration middleware blocks booking request when no_whatsapp is empty.
     */
    public function test_whatsapp_middleware_blocks_seeker_without_whatsapp_number(): void
    {
        $seeker = User::create([
            'name' => 'No WA Seeker',
            'email' => 'nowa@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '',
            'is_verified' => true,
        ]);

        $owner = User::create([
            'name' => 'Owner Jane',
            'email' => 'owner.jane@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Indah',
            'slug' => 'kos-indah',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan No. 5',
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
            'description' => 'Kos indah dekat UNRAM',
            'main_image' => 'properties/indah.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $roomType = RoomType::create([
            'property_id' => $property->id,
            'name' => 'Single Bed',
            'price_per_month' => 1000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Single bed room type',
        ]);

        $this->actingAs($seeker);

        // Try getting the booking creation page
        $responseGet = $this->get(route('booking.create', ['room_type_id' => $roomType->id]));
        $responseGet->assertRedirect(route('profile.edit', ['tab' => 'view-settings']));
        $responseGet->assertSessionHas('error', 'Silakan lengkapi nomor WhatsApp Anda terlebih dahulu sebelum melakukan booking.');

        // Try submitting a new booking
        $responsePost = $this->post(route('booking.store'), [
            'room_type_id' => $roomType->id,
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'duration_months' => 2,
        ]);
        $responsePost->assertRedirect(route('profile.edit', ['tab' => 'view-settings']));
        $responsePost->assertSessionHas('error', 'Silakan lengkapi nomor WhatsApp Anda terlebih dahulu sebelum melakukan booking.');
        
        $this->assertEquals(0, Booking::count());
    }

    /**
     * Test that the WhatsApp configuration middleware allows booking request when no_whatsapp is present.
     */
    public function test_whatsapp_middleware_allows_seeker_with_whatsapp_number(): void
    {
        $seeker = User::create([
            'name' => 'Has WA Seeker',
            'email' => 'haswa@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '081234567890',
            'is_verified' => true,
        ]);

        $owner = User::create([
            'name' => 'Owner Jane',
            'email' => 'owner.jane@test.com',
            'password' => bcrypt('password'),
            'role' => 'owner',
        ]);

        $property = Property::create([
            'user_id' => $owner->id,
            'name' => 'Kos Indah',
            'slug' => 'kos-indah',
            'type' => 'Campur',
            'area' => 'Mataram',
            'address' => 'Jln. Pendidikan No. 5',
            'latitude' => '-8.587063',
            'longitude' => '116.092185',
            'description' => 'Kos indah dekat UNRAM',
            'main_image' => 'properties/indah.png',
            'is_verified' => true,
            'status' => 'published',
        ]);

        $roomType = RoomType::create([
            'property_id' => $property->id,
            'name' => 'Single Bed',
            'price_per_month' => 1000000,
            'total_rooms' => 5,
            'available_rooms' => 3,
            'description' => 'Single bed room type',
        ]);

        $this->actingAs($seeker);

        // Try getting the booking creation page
        $responseGet = $this->get(route('booking.create', ['room_type_id' => $roomType->id]));
        $responseGet->assertStatus(200);

        // Try submitting a new booking
        $responsePost = $this->post(route('booking.store'), [
            'room_type_id' => $roomType->id,
            'check_in_date' => now()->addDays(2)->format('Y-m-d'),
            'duration_months' => 2,
        ]);
        $responsePost->assertRedirect();
        
        $this->assertEquals(1, Booking::count());
    }
}
