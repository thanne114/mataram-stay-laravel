<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Str;

class Property extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'type',
        'area',
        'address',
        'latitude',
        'longitude',
        'description',
        'main_image',
        'is_verified',
        'status',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    // ============================
    // RELATIONSHIPS
    // ============================

    /** Pemilik kos */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /** Tipe kamar yang tersedia di kos ini */
    public function roomTypes(): HasMany
    {
        return $this->hasMany(RoomType::class);
    }

    /** Fasilitas kos (Many-to-Many via pivot facility_property) */
    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class, 'facility_property');
    }

    /** Galeri foto kos */
    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    /** Semua booking melalui room_types */
    public function bookings(): HasManyThrough
    {
        return $this->hasManyThrough(Booking::class, RoomType::class);
    }

    /** Ulasan untuk kos ini */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // ============================
    // ACCESSORS
    // ============================

    /** Harga termurah dari semua tipe kamar */
    public function getLowestPriceAttribute(): ?int
    {
        return $this->roomTypes->min('price_per_month');
    }

    /** Total kamar tersedia di semua tipe */
    public function getAvailableRoomsAttribute(): int
    {
        return $this->roomTypes->sum('available_rooms');
    }

    /** Total semua kamar di semua tipe */
    public function getTotalRoomsAttribute(): int
    {
        return $this->roomTypes->sum('total_rooms');
    }

    /** Rating rata-rata dari ulasan */
    public function getAverageRatingAttribute(): ?float
    {
        $avg = $this->reviews->avg('rating');
        return $avg ? round($avg, 1) : null;
    }

    /** Pseudo-random views count based on ID and current day */
    public function getViewsCountAttribute(): int
    {
        return (($this->id * 7) + now()->dayOfYear) % 28 + 8;
    }

    /** Closest campus to the property */
    public function getClosestCampusAttribute(): array
    {
        $campuses = [
            'UNRAM' => ['name' => 'Universitas Mataram (UNRAM)', 'lat' => -8.587063, 'lng' => 116.092185],
            'UIN_MATARAM' => ['name' => 'UIN Mataram Kampus 2', 'lat' => -8.609817, 'lng' => 116.100646],
            'UIN_MATARAM_1' => ['name' => 'UIN Mataram Kampus 1', 'lat' => -8.582297, 'lng' => 116.094629],
            'UIN_MATARAM_2' => ['name' => 'UIN Mataram Kampus 2', 'lat' => -8.609817, 'lng' => 116.100646],
            'UMMAT' => ['name' => 'Universitas Muhammadiyah Mataram (UMMAT)', 'lat' => -8.5982, 'lng' => 116.1084],
            'POLNAM' => ['name' => 'Politeknik Negeri Mataram (Polnam)', 'lat' => -8.5833, 'lng' => 116.0950],
            'UT_MATARAM' => ['name' => 'Universitas Terbuka Mataram (UT)', 'lat' => -8.5796, 'lng' => 116.1026],
            'UTM' => ['name' => 'Universitas Teknologi Mataram (UTM)', 'lat' => -8.5835, 'lng' => 116.1054],
            'UNBIM' => ['name' => 'Universitas Bhakti Mataram (UNBIM)', 'lat' => -8.6050, 'lng' => 116.0850],
            'IAHN_GDE_PUDJA' => ['name' => 'IAHN Gde Pudja', 'lat' => -8.5990, 'lng' => 116.1165],
            'STIKES_YARSI' => ['name' => 'STIKES Yarsi Mataram', 'lat' => -8.6120, 'lng' => 116.1060],
            'UNMAS' => ['name' => 'Universitas Mahasaraswati Mataram', 'lat' => -8.5925, 'lng' => 116.1105],
        ];

        $propLat = (float) $this->latitude;
        $propLng = (float) $this->longitude;

        if (!$propLat || !$propLng) {
            return [];
        }

        $closest = null;
        $minDist = 999999;

        foreach ($campuses as $key => $campus) {
            $earthRadius = 6371; // km
            $dLat = deg2rad($campus['lat'] - $propLat);
            $dLng = deg2rad($campus['lng'] - $propLng);
            $a = sin($dLat/2) * sin($dLat/2) +
                 cos(deg2rad($propLat)) * cos(deg2rad($campus['lat'])) *
                 sin($dLng/2) * sin($dLng/2);
            $c = 2 * atan2(sqrt($a), sqrt(1-$a));
            $dist = $earthRadius * $c;

            if ($dist < $minDist) {
                $minDist = $dist;
                $closest = $campus;
            }
        }

        if ($closest) {
            if ($minDist < 1.0) {
                $time = round($minDist * 12);
                $time = $time < 1 ? 1 : $time;
                $label = "🚶 {$time} Menit jalan kaki ke {$closest['name']}";
            } else {
                $time = round($minDist * 2);
                $time = $time < 1 ? 1 : $time;
                $label = "🚗 {$time} Menit berkendara ke {$closest['name']}";
            }
            return [
                'name' => $closest['name'],
                'distance' => round($minDist, 2),
                'label' => $label,
            ];
        }

        return [];
    }

    // ============================
    // BOOT (Auto-generate slug)
    // ============================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($property) {
            if (empty($property->slug)) {
                $property->slug = Str::slug($property->name) . '-' . Str::random(5);
            } elseif ($property->isDirty('name') && !$property->isDirty('slug')) {
                $property->slug = Str::slug($property->name) . '-' . Str::random(5);
            }
        });
    }
}
