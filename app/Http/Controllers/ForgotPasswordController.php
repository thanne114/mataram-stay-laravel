<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Menampilkan form permintaan reset password
     */
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Mengirim tautan reset password ke email user
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'exists:users,email'],
        ], [
            'email.required' => 'Alamat email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Alamat email tidak terdaftar di sistem kami.',
        ]);

        // Mengirimkan email menggunakan Password Broker bawaan Laravel
        $status = Password::broker()->sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Tautan untuk menyetel ulang kata sandi berhasil dikirim ke email Anda.');
        }

        return back()->withErrors([
            'email' => 'Gagal mengirimkan email reset password. Silakan coba lagi.',
        ]);
    }
}
