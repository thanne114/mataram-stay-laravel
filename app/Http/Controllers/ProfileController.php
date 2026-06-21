<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendOtpEmail;
use App\Mail\ModerationNotificationMail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Crypt;

class ProfileController extends Controller
{
    // Menampilkan halaman profil
    public function edit()
    {
        $user = Auth::user();
        if ($user->isOwner()) {
            return redirect()->route('dashboard.owner')->with('active_tab', 'settings');
        }
        
        if ($user->isAdmin()) {
            return redirect()->route('dashboard.admin');
        }

        // Fetch seeker's bookings with property and room type relations
        $bookings = \App\Models\Booking::where('user_id', $user->id)
            ->with(['roomType.property'])
            ->latest()
            ->get();

        // Active Booking representing current kos (either Active or Pending)
        $activeBooking = $bookings->where('status', 'Active')->first() ?? $bookings->where('status', 'Pending')->first();

        // Completed Bookings for Riwayat Kos
        $completedBookings = $bookings->where('status', 'Completed');

        // Pending Bookings for Riwayat Pengajuan Sewa
        $pendingBookings = $bookings->where('status', 'Pending');

        $conversations = \App\Models\Conversation::where(function ($query) use ($user) {
                $query->where('seeker_id', $user->id)
                      ->orWhere('owner_id', $user->id);
            })
            ->with([
                'seeker',
                'owner',
                'property',
                'latestMessage'
            ])
            ->withCount([
                'messages as unread_messages_count' => function ($q) {
                    $q->where('sender_id', '!=', auth()->id())
                      ->where('is_read', false);
                }
            ])
            ->get()
            ->sortByDesc(function ($conv) {
                return $conv->latestMessage?->created_at ?? $conv->created_at;
            });

        return view('profile', compact('bookings', 'activeBooking', 'completedBookings', 'pendingBookings', 'conversations'));
    }

    // Memproses update data profil
    public function update(Request $request)
    {
        $user = Auth::user();

        // 1. Validasi data yang dikirim dari form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Pengecekan unique mengabaikan email/username milik user itu sendiri
            'username' => ['nullable', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'no_whatsapp' => ['required', 'string', 'max:20'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            
            // Validasi Rekening Bank (Bila diisi)
            'bank_name' => ['nullable', 'string', 'max:255'],
            'bank_account_name' => ['nullable', 'string', 'max:255'],
            'bank_account_number' => ['nullable', 'string', 'max:50'],
            
            // Validasi Password (Hanya diwajibkan jika user ingin mengganti password)
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'min:8', 'confirmed'],
        ]);

        // 2. Update data teks
        $user->name = $request->name;
        $user->username = $request->username; // <-- BARIS INI SUDAH DIAKTIFKAN
        $user->email = $request->email;
        $user->no_whatsapp = $request->no_whatsapp;
        $user->bank_name = $request->bank_name;
        $user->bank_account_name = $request->bank_account_name;
        $user->bank_account_number = $request->bank_account_number;

        // 3. Update password (Hanya jika kolom password baru diisi)
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        // 4. Upload profile photo jika ada
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->profile_photo);
            }
            $user->profile_photo = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        // 5. Simpan ke database
        $user->save();

        // 6. Kembalikan ke halaman profil dengan pesan sukses (Alert hijau akan muncul)
        return back()->with('success', 'Profil Anda berhasil diperbarui!');
    }

    // Memproses verifikasi identitas (upload KTP/SIM/Paspor)
    public function verifyIdentity(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'identity_type' => ['required', 'string', Rule::in(['ktp', 'sim', 'passport'])],
            'identity_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'selfie_photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        // Hapus foto lama jika ada
        if ($user->identity_photo) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($user->identity_photo);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->identity_photo);
        }
        if ($user->selfie_photo) {
            \Illuminate\Support\Facades\Storage::disk('local')->delete($user->selfie_photo);
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->selfie_photo);
        }

        $identityPhoto = $request->file('identity_photo');
        $selfiePhoto = $request->file('selfie_photo');

        $identityPath = 'identities/' . $identityPhoto->hashName();
        $selfiePath = 'identities/' . $selfiePhoto->hashName();

        // Simpan foto terenkripsi ke disk privat 'local'
        \Illuminate\Support\Facades\Storage::disk('local')->put(
            $identityPath,
            Crypt::encryptString($identityPhoto->get())
        );

        \Illuminate\Support\Facades\Storage::disk('local')->put(
            $selfiePath,
            Crypt::encryptString($selfiePhoto->get())
        );

        $user->identity_type = $request->identity_type;
        $user->identity_photo = $identityPath;
        $user->selfie_photo = $selfiePath;
        $user->is_verified = false; // Requires manual admin approval
        $user->save();

        // Send email notification to Admin(s)
        try {
            $admins = \App\Models\User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                Mail::to($admin->email)->send(new ModerationNotificationMail($user, 'verification_queue_admin'));
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed to send verification queue email to admin: ' . $e->getMessage());
        }

        return back()->with('success', 'Dokumen identitas Anda berhasil diunggah. Menunggu verifikasi dari Administrator!');
    }

    // Menampilkan file identitas secara aman (Secure Route)
    public function showIdentityPhoto($filename)
    {
        $user = Auth::user();
        
        // Cari user yang memiliki KTP atau Selfie dengan nama file tersebut
        $targetUser = \App\Models\User::where('identity_photo', 'identities/' . $filename)
            ->orWhere('selfie_photo', 'identities/' . $filename)
            ->first();

        if (!$targetUser) {
            abort(404, 'File tidak ditemukan.');
        }

        // Otorisasi: Hanya pemilik file atau admin yang boleh mengakses
        if ($user->id !== $targetUser->id && !$user->isAdmin()) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat dokumen ini.');
        }

        $path = 'identities/' . $filename;
        if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File fisik tidak ditemukan.');
        }

        // Baca file terenkripsi dari disk
        $encryptedContent = \Illuminate\Support\Facades\Storage::disk('local')->get($path);

        try {
            // Dekripsi data biner
            $decryptedContent = Crypt::decryptString($encryptedContent);
            
            // Deteksi mime type berdasarkan ekstensi file terlebih dahulu (untuk efisiensi dan kompabilitas test)
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $mimeType = match (strtolower($extension)) {
                'png' => 'image/png',
                'jpg', 'jpeg' => 'image/jpeg',
                'webp' => 'image/webp',
                'gif' => 'image/gif',
                default => null,
            };

            // Fallback ke deteksi biner dinamis jika ekstensi tidak dikenal
            if (!$mimeType) {
                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->buffer($decryptedContent);
            }
        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
            // Fallback untuk file lama yang belum dienkripsi
            $decryptedContent = $encryptedContent;
            $mimeType = \Illuminate\Support\Facades\Storage::disk('local')->mimeType($path);
        }

        return response($decryptedContent)
            ->header('Content-Type', $mimeType)
            ->header('Cache-Control', 'private, max-age=86400');
    }

    // Mengirimkan kode OTP handphone (WhatsApp)
    public function sendPhoneOtp(Request $request)
    {
        $user = Auth::user();
        
        if (empty($user->no_whatsapp)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Silakan lengkapi nomor WhatsApp Anda terlebih dahulu.'
            ], 400);
        }

        $throttleKey = 'send-phone-otp:' . $user->id . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return response()->json([
                'status' => 'error',
                'message' => "Terlalu banyak permintaan OTP. Silakan coba lagi dalam {$minutes} menit."
            ], 429);
        }

        // Generate 4 digit OTP
        $otp = (string) rand(1000, 9999);
        
        // Simpan OTP ke session dengan waktu kedaluwarsa 10 menit
        session([
            'phone_otp' => $otp,
            'phone_otp_expires_at' => now()->addMinutes(10),
            'phone_otp_target' => $user->no_whatsapp
        ]);
        
        // Simulasi pengiriman WhatsApp dengan mencatatnya ke log file
        \Illuminate\Support\Facades\Log::info("WhatsApp OTP sent to {$user->no_whatsapp}: {$otp}");
        
        RateLimiter::hit($throttleKey, 900); // 15 minutes decay

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP verifikasi nomor handphone berhasil dikirim ke nomor WhatsApp ' . $user->no_whatsapp
        ]);
    }

    // Memproses verifikasi nomor handphone (OTP)
    public function verifyPhone(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'otp' => ['required', 'string', 'size:4'],
        ]);

        // Verifikasi terhadap OTP asli yang tersimpan di session
        $sessionOtp = session('phone_otp');
        $expiresAt = session('phone_otp_expires_at');
        $targetPhone = session('phone_otp_target');

        if (!$sessionOtp || !$expiresAt || now()->greaterThan($expiresAt) || $targetPhone !== $user->no_whatsapp) {
            return back()->withErrors(['otp' => 'Kode OTP verifikasi nomor handphone sudah kedaluwarsa atau tidak valid. Silakan kirim ulang OTP.']);
        }

        if ($request->otp !== $sessionOtp) {
            return back()->withErrors(['otp' => 'Kode OTP verifikasi nomor handphone salah.']);
        }

        // Bersihkan session OTP setelah sukses
        session()->forget(['phone_otp', 'phone_otp_expires_at', 'phone_otp_target']);

        $user->phone_verified_at = now();
        $user->save();

        return back()->with('success', 'Nomor handphone Anda berhasil diverifikasi!');
    }

    // Mengirimkan kode OTP email
    public function sendEmailOtp(Request $request)
    {
        $user = Auth::user();
        
        $throttleKey = 'send-otp:' . $user->id . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = RateLimiter::availableIn($throttleKey);
            $minutes = ceil($seconds / 60);
            return response()->json([
                'status' => 'error',
                'message' => "Terlalu banyak permintaan OTP. Silakan coba lagi dalam {$minutes} menit."
            ], 429);
        }

        // Generate 4 digit OTP
        $otp = (string) rand(1000, 9999);
        
        // Simpan OTP ke session dengan waktu kedaluwarsa 10 menit
        session([
            'email_otp' => $otp,
            'email_otp_expires_at' => now()->addMinutes(10),
            'email_otp_target' => $user->email
        ]);
        
        // Kirim email
        try {
            Mail::to($user->email)->send(new SendOtpEmail($otp, $user->name));
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal mengirim email OTP: ' . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal mengirim email verifikasi. Silakan coba beberapa saat lagi.'
            ], 500);
        }
        
        RateLimiter::hit($throttleKey, 900); // 15 minutes decay

        return response()->json([
            'status' => 'success',
            'message' => 'Kode OTP berhasil dikirim ke email ' . $user->email
        ]);
    }

    // Memproses verifikasi email (OTP)
    public function verifyEmail(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'otp' => ['required', 'string', 'size:4'],
        ]);

        $sessionOtp = session('email_otp');
        $expiresAt = session('email_otp_expires_at');
        $targetEmail = session('email_otp_target');

        if (!$sessionOtp || !$expiresAt || now()->greaterThan($expiresAt) || $targetEmail !== $user->email) {
            return back()->withErrors(['otp' => 'Kode OTP verifikasi email sudah kedaluwarsa atau tidak valid. Silakan kirim ulang OTP.']);
        }

        if ($request->otp !== $sessionOtp) {
            return back()->withErrors(['otp' => 'Kode OTP verifikasi email salah.']);
        }

        // Hapus OTP dari session
        session()->forget(['email_otp', 'email_otp_expires_at', 'email_otp_target']);

        $user->email_verified_at = now();
        $user->save();

        return back()->with('success', 'Email Anda berhasil diverifikasi!');
    }
}