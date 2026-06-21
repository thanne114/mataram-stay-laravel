<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDeactivationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test authenticated user can deactivate their own account.
     */
    public function test_authenticated_user_can_deactivate_own_account(): void
    {
        $user = User::create([
            'name' => 'User to Deactivate',
            'email' => 'deactivate@test.com',
            'password' => bcrypt('password'),
            'role' => 'seeker',
        ]);

        $response = $this->actingAs($user)
            ->delete(route('profile.deactivate'));

        // Assert session is invalidated and user is redirected to home with success message
        $response->assertRedirect(route('home'));
        $response->assertSessionHas('success', 'Akun Anda telah berhasil dinonaktifkan secara permanen.');

        // Assert user record is deleted from database
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'deactivate@test.com',
        ]);
        
        // Assert user is logged out
        $this->assertGuest();
    }

    /**
     * Test unauthenticated guest cannot deactivate account.
     */
    public function test_guest_cannot_deactivate_account(): void
    {
        $response = $this->delete(route('profile.deactivate'));

        // Assert user is redirected to login (auth middleware works)
        $response->assertRedirect(route('login'));
    }
}
