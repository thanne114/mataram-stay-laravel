<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'no_whatsapp',
        'profile_photo',
        'identity_type',
        'identity_photo',
        'selfie_photo',
        'is_verified',
        'phone_verified_at',
        'social_id',
        'auth_provider',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_verified' => 'boolean',
        ];
    }

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Properti kos milik owner ini */
    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /** Booking milik seeker ini */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /** Review yang ditulis user ini */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ============================
    // ROLE & VERIFICATION HELPERS
    // ============================

    public function isOwner(): bool
    {
        return $this->role === 'owner';
    }

    public function isSeeker(): bool
    {
        return $this->role === 'seeker';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPhoneVerified(): bool
    {
        return !is_null($this->phone_verified_at);
    }

    public function isIdentityVerified(): bool
    {
        return (bool)$this->is_verified;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        \Illuminate\Support\Facades\Mail::to($this->email)->send(new \App\Mail\ForgotPasswordMail($token, $this));
    }
}