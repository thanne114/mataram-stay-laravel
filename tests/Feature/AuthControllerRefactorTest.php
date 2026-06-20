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
