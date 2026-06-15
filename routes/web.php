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

// ============================================
// RUTE PUBLIK (Tanpa Login)
// ============================================

Route::get('/', [HomeController::class, 'index']);
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/api/map-data', [SearchController::class, 'mapData'])->name('api.map-data');
Route::get('/kos/{kos:slug}', [PropertyController::class, 'show'])->name('property.show');
Route::post('/payment/notification', [\App\Http\Controllers\PaymentController::class, 'notification'])->name('payment.notification');

use App\Http\Controllers\GoogleAuthController;

// ============================================
// RUTE GUEST (Belum Login)
// ============================================

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate']);
    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'store'])->middleware('throttle:3,10');

    // Google OAuth Routes
    Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);
});

// ============================================
// RUTE AUTH (Sudah Login - Semua Role)
// ============================================

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
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

    // Verifikasi & Update Status Booking
    Route::post('/booking/{booking}/verify', [BookingController::class, 'verify'])->name('booking.verify');
    Route::post('/booking/{booking}/update-status', [BookingController::class, 'updateStatus'])->name('booking.update-status');
});

// ============================================
// RUTE SEEKER (Khusus Pencari Kos)
// ============================================

Route::middleware(['auth', 'role:seeker'])->group(function () {
    Route::get('/dashboard-seeker', [DashboardController::class, 'seeker'])->name('dashboard.seeker');

    // Booking
    Route::get('/booking/create', [BookingController::class, 'create'])->name('booking.create');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');

    // Upload Bukti Pembayaran
    Route::post('/booking/{booking}/upload-proof', [BookingController::class, 'uploadProof'])->name('booking.upload-proof');

    // Transaksi Seeker (booking miliknya)
    Route::get('/seeker/transactions', [TransactionController::class, 'seekerIndex'])->name('transactions.seeker');

    // Review
    Route::post('/review', [ReviewController::class, 'store'])->name('review.store');
});

// ============================================
// RUTE ADMIN (Khusus Administrator)
// ============================================
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard-admin', [DashboardController::class, 'admin'])->name('dashboard.admin');
    Route::post('/admin/verify-seeker/{user}', [DashboardController::class, 'verifySeeker'])->name('admin.verify-seeker');
    Route::post('/admin/approve-property/{property}', [DashboardController::class, 'approveProperty'])->name('admin.approve-property');
    Route::post('/admin/update-settings', [DashboardController::class, 'updateSettings'])->name('admin.update-settings');
});

// ============================================
// RUTE SHARED AUTH (Owner & Seeker bisa akses)
// ============================================

Route::middleware('auth')->group(function () {
    Route::get('/booking/{booking}', [BookingController::class, 'show'])->name('booking.show');
    Route::post('/profile/verify-identity', [ProfileController::class, 'verifyIdentity'])->name('profile.verify-identity');
    Route::post('/profile/verify-phone', [ProfileController::class, 'verifyPhone'])->name('profile.verify-phone');
    Route::post('/profile/send-email-otp', [ProfileController::class, 'sendEmailOtp'])->name('profile.send-email-otp');
    Route::post('/profile/verify-email', [ProfileController::class, 'verifyEmail'])->name('profile.verify-email');
    Route::get('/profile/identity-photo/{filename}', [ProfileController::class, 'showIdentityPhoto'])->name('profile.identity-photo');
});