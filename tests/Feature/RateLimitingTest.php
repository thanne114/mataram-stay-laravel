<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class RateLimitingTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name' => 'Seeker Test',
            'username' => 'seeker_test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
            'no_whatsapp' => '08123456789',
        ]);
    }

    /**
     * Test login rate limiting blocks after 5 attempts.
     */
    public function test_login_rate_limiting_blocks_after_five_failed_attempts(): void
    {
        RateLimiter::clear(strtolower($this->user->email) . '|127.0.0.1');

        // Make 5 failed attempts
        for ($i = 0; $i < 5; $i++) {
            $response = $this->from('/login')->post('/login', [
                'email' => $this->user->email,
                'password' => 'wrong_password',
            ]);

            $response->assertRedirect('/login');
            $response->assertSessionHasErrors('email');
            
            // Check that it's the standard wrong credentials message
            $errors = session('errors');
            $this->assertEquals('Email atau password salah.', $errors->first('email'));
        }

        // 6th attempt should trigger rate limit block
        $response = $this->from('/login')->post('/login', [
            'email' => $this->user->email,
            'password' => 'wrong_password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');

        $errors = session('errors');
        $this->assertStringContainsString('Terlalu banyak percobaan login', $errors->first('email'));
    }

    /**
     * Test OTP request rate limiting blocks after 3 requests.
     */
    public function test_otp_request_rate_limiting_blocks_after_three_requests(): void
    {
        Mail::fake();
        
        $this->actingAs($this->user);
        RateLimiter::clear('send-otp:' . $this->user->id . '|127.0.0.1');

        // First 3 requests should be successful
        for ($i = 0; $i < 3; $i++) {
            $response = $this->postJson('/profile/send-email-otp');
            $response->assertStatus(200);
            $response->assertJsonPath('status', 'success');
        }

        // 4th request should return 429 Too Many Requests
        $response = $this->postJson('/profile/send-email-otp');
        $response->assertStatus(429);
        $response->assertJsonPath('status', 'error');
        $response->assertJsonMissingPath('otp');
        $this->assertStringContainsString('Terlalu banyak permintaan OTP', $response->json('message'));
    }
}
