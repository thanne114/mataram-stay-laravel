<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'room_type_id',
        'check_in_date',
        'duration_months',
        'total_price',
        'room_subtotal',
        'admin_fee',
        'commission_fee',
        'net_owner_amount',
        'status',
        'payment_status',
        'payment_proof',
        'payment_token',
        'is_approved',
        'escrow_status',
        'payout_status',
        'payout_reference',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'is_approved' => 'boolean',
    ];

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Pencari kos yang melakukan pemesanan */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** Tipe kamar yang dipesan */
    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    /** Ulasan untuk booking ini */
    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    // ============================
    // ACCESSORS
    // ============================

    /** Akses langsung ke properti melalui roomType */
    public function getPropertyAttribute(): ?Property
    {
        return $this->roomType?->property;
    }

    /** Tanggal check-out berdasarkan durasi */
    public function getCheckOutDateAttribute()
    {
        return $this->check_in_date?->addMonths($this->duration_months);
    }
}
