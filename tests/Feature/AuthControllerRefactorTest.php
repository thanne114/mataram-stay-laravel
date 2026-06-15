<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AuthControllerRefactorTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful registration with valid data.
     */
    public function test_user_can_register_with_valid_data(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'SecureP@ss123!',
            'password_confirmation' => 'SecureP@ss123!',
            'role' => 'seeker',
            'no_whatsapp' => '081234567890',
        ]);

        $response->assertRedirect('/dashboard-seeker');
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com',
            'username' => 'johndoe',
            'role' => 'seeker',
        ]);
        
        $this->assertAuthenticated();
    }

    /**
     * Test registration fails with weak password.
     */
    public function test_registration_fails_with_weak_password(): void
    {
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe',
            'email' => 'johndoe@example.com',
            'password' => 'weak',
            'password_confirmation' => 'weak',
            'role' => 'seeker',
            'no_whatsapp' => '081234567890',
        ]);

        $response->assertSessionHasErrors('password');
        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /**
     * Test registration rate limiter blocks after 3 attempts.
     */
    public function test_registration_rate_limiting(): void
    {
        // Make 3 successful/redirection requests
        for ($i = 0; $i < 3; $i++) {
            $response = $this->post('/register', [
                'name' => 'John Doe',
                'username' => 'johndoe' . $i,
                'email' => 'johndoe' . $i . '@example.com',
                'password' => 'SecureP@ss123!',
                'password_confirmation' => 'SecureP@ss123!',
                'role' => 'seeker',
                'no_whatsapp' => '081234567890',
            ]);
            $response->assertRedirect();
            auth()->logout();
        }

        // 4th request should trigger 429 Too Many Requests
        $response = $this->post('/register', [
            'name' => 'John Doe',
            'username' => 'johndoe3',
            'email' => 'johndoe3@example.com',
            'password' => 'SecureP@ss123!',
            'password_confirmation' => 'SecureP@ss123!',
            'role' => 'seeker',
            'no_whatsapp' => '081234567890',
        ]);

        $response->assertStatus(429);
    }

    /**
     * Test seeker is redirected to seeker dashboard when accessing home page.
     */
    public function test_seeker_is_redirected_to_seeker_dashboard(): void
    {
        $user = User::create([
            'name' => 'Seeker User',
            'email' => 'seeker.home@example.com',
            'role' => 'seeker',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/dashboard-seeker');
    }

    /**
     * Test owner can access home page.
     */
    public function test_owner_can_access_home_page(): void
    {
        $user = User::create([
            'name' => 'Owner User',
            'email' => 'owner.home@example.com',
            'role' => 'owner',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertStatus(200);
        $response->assertSee('Halo, Owner User');
    }
}
