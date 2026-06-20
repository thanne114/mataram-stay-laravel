<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     */
    public function redirect(Request $request)
    {
        // Store the target role (seeker / owner) in the session
        $role = $request->query('role', 'seeker');
        if (!in_array($role, ['seeker', 'owner'])) {
            $role = 'seeker';
        }

        session(['google_sso_role' => $role]);

        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     */
    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            Log::error('Google OAuth callback failed: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Gagal autentikasi dengan Google. Silakan coba lagi.');
        }

        // Look for existing user by email
        $user = User::where('email', $googleUser->getEmail())->first();

        if ($user) {
            // Update google_id and avatar
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'social_id' => $googleUser->getId(),
                'auth_provider' => 'google',
            ]);
        } else {
            // Register a new user
            $role = session('google_sso_role', 'seeker');
            
            // Clean/Generate unique username
            $baseUsername = Str::slug($googleUser->getName() ?: 'user', '');
            $username = $baseUsername . rand(100, 999);
            while (User::where('username', $username)->exists()) {
                $username = $baseUsername . rand(100, 999);
            }

            $user = User::create([
                'name' => $googleUser->getName() ?: 'Google User',
                'email' => $googleUser->getEmail(),
                'username' => $username,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'social_id' => $googleUser->getId(),
                'auth_provider' => 'google',
                'role' => $role,
                'email_verified_at' => now(), // Mark email as verified since it is authenticated by Google
                'password' => null, // Left empty
            ]);
        }

        // Clean up session role
        session()->forget('google_sso_role');

        // Authenticate the user
        Auth::login($user);

        // Redirect based on role
        if ($user->role === 'owner' || $user->role === 'admin') {
            return redirect()->route('dashboard.owner')->with('success', 'Selamat datang!');
        }

        return redirect()->route('dashboard.seeker')->with('success', 'Selamat datang, Pencari Kos!');
    }
}
