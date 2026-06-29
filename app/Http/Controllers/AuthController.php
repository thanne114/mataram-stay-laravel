<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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



    // --- Bagian Logout ---
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}