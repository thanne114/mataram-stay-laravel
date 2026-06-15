<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'price_per_month',
        'total_rooms',
        'available_rooms',
        'description',
    ];

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Kos induk dari tipe kamar ini */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /** Semua booking untuk tipe kamar ini */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}
