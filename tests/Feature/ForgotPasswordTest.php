<?php

namespace Tests\Feature;

use App\Models\User;
use App\Mail\ForgotPasswordMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ForgotPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function forgot_password_page_is_accessible()
    {
        $response = $this->get(route('password.request'));
        $response->assertStatus(200);
        $response->assertSee('Lupa Kata Sandi');
    }

    /** @test */
    public function reset_link_can_be_requested_and_custom_mail_is_queued()
    {
        Mail::fake();

        $user = User::factory()->create([
            'email' => 'seeker@example.com',
            'name' => 'John Doe'
        ]);

        $response = $this->post(route('password.email'), [
            'email' => 'seeker@example.com'
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        Mail::assertQueued(ForgotPasswordMail::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email) && $mail->user->id === $user->id;
        });
    }

    /** @test */
    public function reset_password_page_is_accessible_with_token()
    {
        $user = User::factory()->create([
            'email' => 'seeker@example.com'
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->get(route('password.reset', ['token' => $token, 'email' => $user->email]));
        
        $response->assertStatus(200);
        $response->assertSee('Kata Sandi Baru');
        $response->assertSee($token);
    }

    /** @test */
    public function password_can_be_reset_with_valid_token_and_logs_in()
    {
        $user = User::factory()->create([
            'email' => 'seeker@example.com',
            'password' => Hash::make('oldpassword'),
            'role' => 'seeker'
        ]);

        $token = Password::broker()->createToken($user);

        $response = $this->post(route('password.update'), [
            'token' => $token,
            'email' => 'seeker@example.com',
            'password' => 'newpassword123',
            'password_confirmation' => 'newpassword123',
        ]);

        $response->assertRedirect('/dashboard-seeker');
        
        // Assert password was updated
        $user->refresh();
        $this->assertTrue(Hash::check('newpassword123', $user->password));

        // Assert user is logged in
        $this->assertAuthenticatedAs($user);
    }
}
