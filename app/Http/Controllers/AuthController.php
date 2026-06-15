<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;

use App\Http\Requests\RegisterRequest;
use App\Actions\RegisterUserAction;

class AuthController extends Controller
{
    // --- Bagian Register ---
    public function register() {
        return view('register');
    }

    public function store(RegisterRequest $request, RegisterUserAction $registerUserAction) 
    {
        $user = $registerUserAction->execute($request->validated());

        // Langsung login-kan user setelah berhasil daftar
        auth()->login($user);

        // Redirect berdasarkan role
        if ($user->role === 'admin') {
            return redirect()->route('dashboard.admin')->with('success', 'Selamat datang, Administrator!');
        }
        if ($user->role === 'owner') {
            return redirect('/')->with('success', 'Selamat datang!');
        }

        return redirect('/dashboard-seeker')->with('success', 'Selamat datang, Pencari Kos!');
    }

    // --- Bagian Login ---
    public function login() {
        return view('login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $throttleKey = Str::lower($credentials['email']) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            return back()->withErrors([
                'email' => "Terlalu banyak percobaan login. Silakan coba lagi dalam {$seconds} detik.",
            ])->onlyInput('email');
        }

        if (Auth::attempt($credentials)) {
            RateLimiter::clear($throttleKey);
            $request->session()->regenerate();
            
            // Redirect berdasarkan role setelah login berhasil
            $user = Auth::user();
            if ($user->role === 'admin') {
                return redirect()->intended('/dashboard-admin');
            }
            if ($user->role === 'owner') {
                return redirect()->intended('/');
            }
            return redirect()->intended('/dashboard-seeker');
        }

        RateLimiter::hit($throttleKey, 60);

        // Kembali ke halaman login jika gagal, dengan membawa pesan error dan input email sebelumnya
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email'); 
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