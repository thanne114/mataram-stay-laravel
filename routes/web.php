<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\TransactionController;

Route::get('/auth/bypass/{role}', function ($role) {
    if (app()->environment('production')) {
        abort(403);
    }
    $email = match($role) {
        'seeker' => 'seeker@mataramstay.com',
        'owner' => 'owner@mataramstay.com',
        'admin' => 'mataramstay@gmail.com',
        default => abort(404)
    };
    $user = \App\Models\User::where('email', $email)->firstOrFail();
    auth()->login($user);
    
    $redirect = match($role) {
        'seeker' => route('dashboard.seeker'),
        'owner' => route('dashboard.owner'),
        'admin' => route('dashboard.admin'),
    };
    return redirect($redirect);
});

// ============================================
// RUTE PUBLIK (Tanpa Login)
// ============================================

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/api/map-data', [SearchController::class, 'mapData'])->name('api.map-data');
Route::get('/kos/{kos:slug}', [PropertyController::class, 'show'])->name('property.show');
Route::post('/payment/notification', [\App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');
Route::get('/kampus', [HomeController::class, 'kampusDirectory'])->name('kampus.index');
Route::get('/bantuan', function () {
    $commissionRate = \App\Models\Setting::getValue('commission_rate', 5);
    return view('bantuan', compact('commissionRate'));
})->name('bantuan');

Route::view('/wisata', 'wisata')->name('wisata');
Route::view('/syarat-ketentuan', 'syarat-ketentuan')->name('syarat-ketentuan');
Route::view('/kebijakan-privasi', 'kebijakan-privasi')->name('kebijakan-privasi');
Route::view('/tentang', 'tentang')->name('tentang');
Route::view('/blog', 'blog')->name('blog');


use App\Http\Controllers\GoogleAuthController;

// ============================================
// RUTE GUEST (Belum Login)
// ============================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/register', [AuthController::class, 'register'])->name('register');



    // Google OAuth Routes
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback']);

    // Google SSO Onboarding Role Selection
    Route::get('/auth/google/choose-role', [GoogleAuthController::class, 'showChooseRole'])->name('auth.google.choose-role');
    Route::post('/auth/google/choose-role', [GoogleAuthController::class, 'saveChooseRole'])->name('auth.google.choose-role.save');
});

// ============================================
// RUTE AUTH (Sudah Login - Semua Role)
// ============================================

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Chat Internal
    Route::get('/chat', [\App\Http\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('/chat/{conversation}', [\App\Http\Controllers\ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{conversation}/send', [\App\Http\Controllers\ChatController::class, 'store'])->name('chat.send');
    Route::post('/chat/start/{property}', [\App\Http\Controllers\ChatController::class, 'start'])->name('chat.start');
});

// ============================================
// RUTE OWNER (Khusus Pemilik Kos)
// ============================================

Route::middleware(['auth', 'role:owner,admin'])->group(function () {
    Route::get('/dashboard-owner', [DashboardController::class, 'owner'])->name('dashboard.owner');

    // CRUD Properti
    Route::get('/property/create', [PropertyController::class, 'create'])->name('property.create');
    Route::post('/property', [PropertyController::class, 'store'])->name('property.store');
    Route::get('/property/{property}/edit', [PropertyController::class, 'edit'])->name('property.edit');
    Route::put('/property/{property}', [PropertyController::class, 'update'])->name('property.update');
    Route::delete('/property/{property}', [PropertyController::class, 'destroy'])->name('property.destroy');

    // Transaksi Owner (booking masuk ke properti)
    Route::get('/owner/transactions', [TransactionController::class, 'ownerIndex'])->name('transactions.owner');
    Route::get('/owner/live-transactions', [DashboardController::class, 'ownerLiveTransactions'])->name('owner.live-transactions');

    // Verifikasi & Update Status Booking
    Route::post('/booking/{booking}/verify', [BookingController::class, 'verify'])->name('booking.verify');
    Route::post('/booking/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
    Route::post('/booking/{booking}/approve', [BookingController::class, 'approve'])->name('booking.approve');
    Route::post('/booking/{booking}/reject', [BookingController::class, 'reject'])->name('booking.reject');
});

// ============================================
// RUTE SEEKER (Khusus Pencari Kos)
// ============================================

Route::middleware(['auth', 'role:seeker'])->group(function () {
    Route::get('/dashboard-seeker', [DashboardController::class, 'seeker'])->name('dashboard.seeker');

    // Booking (Protected by WhatsApp check)
    Route::middleware('ensure.whatsapp')->group(function () {
        Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
        Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    });

    // Upload Bukti Pembayaran
    Route::post('/booking/{booking}/upload-proof', [BookingController::class, 'uploadProof'])->name('booking.upload-proof');
    Route::post('/booking/{booking}/cancel', [BookingController::class, 'cancel'])->name('booking.cancel');

    // Transaksi Seeker (booking miliknya)
    Route::get('/seeker/transactions', [TransactionController::class, 'seekerIndex'])->name('transactions.seeker');
    Route::get('/seeker/live-transactions', [DashboardController::class, 'seekerLiveTransactions'])->name('seeker.live-transactions');

    // Review
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});

// ============================================
// RUTE ADMIN (Khusus Administrator)
// ============================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::get('/admin/dashboard-stats', [DashboardController::class, 'adminStats'])->name('admin.dashboard-stats');
    Route::post('/admin/verify-seeker/{user}', [DashboardController::class, 'verifySeeker'])->name('admin.verify-seeker');
    Route::post('/admin/reject-seeker/{user}', [DashboardController::class, 'rejectSeeker'])->name('admin.reject-seeker');
    Route::post('/admin/approve-property/{property}', [DashboardController::class, 'approveProperty'])->name('admin.approve-property');
    Route::post('/admin/property/{property}/reject', [DashboardController::class, 'reject'])->name('admin.property.reject');
    Route::post('/admin/update-settings', [DashboardController::class, 'updateSettings'])->name('admin.update-settings');
    Route::post('/admin/booking/{booking}/refund', [DashboardController::class, 'refundBooking'])->name('admin.booking.refund');
});

// ============================================
// RUTE SHARED AUTH (Owner & Seeker bisa akses)
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/profile/verify-identity', [ProfileController::class, 'verifyIdentity'])->name('profile.verify-identity');
    Route::post('/profile/send-phone-otp', [ProfileController::class, 'sendPhoneOtp'])->name('profile.send-phone-otp');
    Route::post('/profile/verify-phone', [ProfileController::class, 'verifyPhone'])->name('profile.verify-phone');
    Route::post('/profile/send-email-otp', [ProfileController::class, 'sendEmailOtp'])->name('profile.send-email-otp');
    Route::post('/profile/verify-email', [ProfileController::class, 'verifyEmail'])->name('profile.verify-email');
    Route::get('/profile/identity-photo/{filename}', [ProfileController::class, 'showIdentityPhoto'])->name('profile.identity-photo');
    Route::get('/booking/payment-proof/{filename}', [BookingController::class, 'showPaymentProof'])->name('booking.payment-proof');
    Route::delete('/profile/deactivate', [ProfileController::class, 'deactivate'])->name('profile.deactivate');
});