<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

class ResetPasswordController extends Controller
{
    /**
     * Menampilkan form setel ulang kata sandi
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with([
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Memproses penggantian kata sandi baru
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => ['required', 'email', 'exists:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'token.required' => 'Token reset tidak valid atau kosong.',
            'email.required' => 'Alamat email wajib diisi.',
            'email.exists' => 'Alamat email tidak terdaftar.',
            'password.required' => 'Kata sandi baru wajib diisi.',
            'password.min' => 'Kata sandi baru harus minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
        ]);

        // Eksekusi pembaruan kata sandi menggunakan Password Broker
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            // Dapatkan user dan langsung login-kan
            $user = \App\Models\User::where('email', $request->email)->first();
            auth()->login($user);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('dashboard.admin')->with('success', 'Kata sandi berhasil diperbarui! Selamat datang kembali.');
            }
            if ($user->role === 'owner') {
                return redirect('/')->with('success', 'Kata sandi berhasil diperbarui! Selamat datang kembali.');
            }
            return redirect('/dashboard-seeker')->with('success', 'Kata sandi berhasil diperbarui! Selamat datang kembali.');
        }

        return back()->withErrors([
            'email' => 'Gagal memperbarui kata sandi. Token mungkin sudah kedaluwarsa atau tidak valid.',
        ]);
    }
}
