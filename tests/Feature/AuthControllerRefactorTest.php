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
     * Test owner is redirected to owner dashboard when accessing home page.
     */
    public function test_owner_is_redirected_to_owner_dashboard(): void
    {
        $user = User::create([
            'name' => 'Owner User',
            'email' => 'owner.home@example.com',
            'role' => 'owner',
        ]);

        $response = $this->actingAs($user)->get('/');
        $response->assertRedirect('/dashboard-owner');
    }
}
