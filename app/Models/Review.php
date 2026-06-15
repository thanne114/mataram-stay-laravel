<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    protected $fillable = [
        'booking_id',
        'property_id',
        'user_id',
        'rating',
        'comment',
    ];

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Booking yang direview */
    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    /** Kos yang direview */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /** User yang menulis review */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
