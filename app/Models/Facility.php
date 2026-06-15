<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Facility extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Kos-kos yang memiliki fasilitas ini */
    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'facility_property');
    }
}
