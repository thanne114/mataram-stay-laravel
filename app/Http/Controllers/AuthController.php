<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    // --- Bagian Register ---
    public function register() {
        return view('register');
    }

    // --- Bagian Login ---
    public function login() {
        return view('login');
    }

    // Menampilkan form login admin
    public function showAdminLoginForm() {
        return view('auth.admin_login');
    }

    // Memproses login admin dengan proteksi Rate Limiting
    public function adminLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $throttleKey = 'admin-login-attempt:' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return back()->withErrors(['email' => "Terlalu banyak percobaan masuk. Silakan coba lagi dalam {$minutes} menit."])->withInput();
        }

        // Cek apakah email terdaftar sebagai admin
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->role !== 'admin') {
            RateLimiter::hit($throttleKey, 900); // 15 minutes block
            return back()->withErrors(['email' => 'Kredensial login admin tidak cocok atau akun Anda bukan admin.'])->withInput();
        }

        // Cek password dan login
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->boolean('remember'))) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            return redirect()->route('dashboard.admin');
        }

        RateLimiter::hit($throttleKey, 900); // 15 minutes block
        return back()->withErrors(['email' => 'Kredensial login admin tidak cocok.'])->withInput();
    }

    // --- Bagian Logout ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}