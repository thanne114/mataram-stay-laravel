<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class PhoneOtpVerificationTest extends TestCase
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
    public function test_phone_otp_request_rate_limiting_blocks_after_three_requests(): void
    {
        $this->actingAs($this->user);
        RateLimiter::clear('send-phone-otp:' . $this->user->id . '|127.0.0.1');

        // First 3 requests should be successful
        for ($i = 0; $i < 3; $i++) {
            $response = $this->postJson('/profile/send-phone-otp');
            $response->assertStatus(200);
            $response->assertJsonPath('status', 'success');
        }

        // 4th request should return 429 Too Many Requests
        $response = $this->postJson('/profile/send-phone-otp');
        $response->assertStatus(429);
        $response->assertJsonPath('status', 'error');
        $this->assertStringContainsString('Terlalu banyak permintaan OTP', $response->json('message'));
    }

    /**
     * Test verifying phone with correct session OTP succeeds.
     */
    public function test_verify_phone_with_correct_otp_succeeds(): void
    {
        $this->actingAs($this->user);

        // Generate OTP
        $response = $this->postJson('/profile/send-phone-otp');
        $response->assertStatus(200);

        // Fetch OTP from session
        $otp = session('phone_otp');
        $this->assertNotNull($otp);

        // Post correct OTP
        $responseVerify = $this->post('/profile/verify-phone', [
            'otp' => $otp
        ]);

        $responseVerify->assertRedirect();
        $responseVerify->assertSessionHas('success', 'Nomor handphone Anda berhasil diverifikasi!');
        
        $this->user->refresh();
        $this->assertTrue($this->user->isPhoneVerified());
    }

    /**
     * Test verifying phone with incorrect OTP fails.
     */
    public function test_verify_phone_with_incorrect_otp_fails(): void
    {
        $this->actingAs($this->user);

        // Generate OTP
        $response = $this->postJson('/profile/send-phone-otp');
        $response->assertStatus(200);

        // Post incorrect OTP
        $responseVerify = $this->post('/profile/verify-phone', [
            'otp' => '9999' // incorrect OTP
        ]);

        $responseVerify->assertSessionHasErrors(['otp']);
        $this->user->refresh();
        $this->assertFalse($this->user->isPhoneVerified());
    }

    /**
     * Test verifying phone without sending OTP first fails.
     */
    public function test_verify_phone_without_otp_in_session_fails(): void
    {
        $this->actingAs($this->user);

        $responseVerify = $this->post('/profile/verify-phone', [
            'otp' => '1234'
        ]);

        $responseVerify->assertSessionHasErrors(['otp']);
        $this->user->refresh();
        $this->assertFalse($this->user->isPhoneVerified());
    }
}
