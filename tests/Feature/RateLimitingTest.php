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
