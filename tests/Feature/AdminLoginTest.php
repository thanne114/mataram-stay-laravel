<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\RateLimiter;
use Tests\TestCase;

class AdminLoginTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test admin login page is accessible.
     */
    public function test_admin_login_page_is_accessible(): void
    {
        $response = $this->get(route('admin.login'));
        $response->assertStatus(200);
        $response->assertSee('Pintu Masuk Admin');
    }

    /**
     * Test admin can login with correct credentials.
     */
    public function test_admin_can_login_with_correct_credentials(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@mataramstay.my.id',
            'password' => bcrypt('!KamiFfLrp26'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => '!KamiFfLrp26',
        ]);

        // Assert redirected to admin dashboard
        $response->assertRedirect(route('dashboard.admin'));
        $this->assertAuthenticatedAs($admin);
    }

    /**
     * Test non-admin user cannot login via admin portal.
     */
    public function test_non_admin_cannot_login_via_admin_portal(): void
    {
        $seeker = User::create([
            'name' => 'Seeker Test',
            'email' => 'seeker@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'seeker@test.com',
            'password' => 'password',
        ]);

        // Assert session has error and user is redirected back
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test invalid password fails to login.
     */
    public function test_invalid_password_fails_to_login(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@mataramstay.my.id',
            'password' => bcrypt('!KamiFfLrp26'),
            'role' => 'admin',
        ]);

        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => 'wrongpassword',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test login rate limiting blocks after 3 failed attempts.
     */
    public function test_admin_login_rate_limiting(): void
    {
        $admin = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@mataramstay.my.id',
            'password' => bcrypt('!KamiFfLrp26'),
            'role' => 'admin',
        ]);

        // Attempt 1: Failed
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => 'wrong1',
        ]);
        $response->assertSessionHasErrors('email');

        // Attempt 2: Failed
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => 'wrong2',
        ]);
        $response->assertSessionHasErrors('email');

        // Attempt 3: Failed
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => 'wrong3',
        ]);
        $response->assertSessionHasErrors('email');

        // Attempt 4: Blocked by rate limiter
        $response = $this->post(route('admin.login.submit'), [
            'email' => 'admin@mataramstay.my.id',
            'password' => 'wrong4',
        ]);

        $response->assertSessionHasErrors('email');
        $this->assertStringContainsString('Terlalu banyak percobaan masuk', session('errors')->first('email'));
    }
}
