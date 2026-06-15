<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\User as SocialiteUser;
use Tests\TestCase;

class GoogleSSOTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test redirect to Google stores role in session and returns redirect response.
     */
    public function test_redirect_to_google(): void
    {
        // Mock Socialite redirect
        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->expects($this->once())
            ->method('redirect')
            ->willReturn(redirect('https://accounts.google.com/o/oauth2/v2/auth'));

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('/auth/google/redirect?role=owner');

        $response->assertRedirect('https://accounts.google.com/o/oauth2/v2/auth');
        $this->assertEquals('owner', session('google_register_role'));
    }

    /**
     * Test Google callback registers new user when email does not exist.
     */
    public function test_google_callback_registers_new_user(): void
    {
        // Setup mock user from Google
        $googleUser = $this->createMock(SocialiteUser::class);
        $googleUser->method('getId')->willReturn('google-id-12345');
        $googleUser->method('getName')->willReturn('Google Seeker');
        $googleUser->method('getEmail')->willReturn('seeker.google@example.com');

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->method('user')->willReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        // Set target role in session
        session(['google_register_role' => 'seeker']);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/dashboard-seeker');
        $this->assertDatabaseHas('users', [
            'email' => 'seeker.google@example.com',
            'social_id' => 'google-id-12345',
            'auth_provider' => 'google',
            'role' => 'seeker',
        ]);
        
        $this->assertAuthenticated();
    }

    /**
     * Test Google callback logs in and links existing user by email.
     */
    public function test_google_callback_logs_in_and_links_existing_user(): void
    {
        // Pre-create user with same email but no social login
        $user = User::create([
            'name' => 'Existing Seeker',
            'email' => 'existing.seeker@example.com',
            'role' => 'seeker',
            'password' => bcrypt('password'),
        ]);

        // Setup mock user from Google
        $googleUser = $this->createMock(SocialiteUser::class);
        $googleUser->method('getId')->willReturn('google-id-existing');
        $googleUser->method('getName')->willReturn('Google Seeker');
        $googleUser->method('getEmail')->willReturn('existing.seeker@example.com');

        $provider = $this->createMock(\Laravel\Socialite\Two\GoogleProvider::class);
        $provider->method('user')->willReturn($googleUser);

        Socialite::shouldReceive('driver')
            ->with('google')
            ->andReturn($provider);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('/dashboard-seeker');
        
        // Assert user has been linked in database
        $this->assertDatabaseHas('users', [
            'email' => 'existing.seeker@example.com',
            'social_id' => 'google-id-existing',
            'auth_provider' => 'google',
        ]);
        
        $this->assertAuthenticatedAs($user);
    }
}
