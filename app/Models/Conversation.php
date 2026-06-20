<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    protected $fillable = [
        'seeker_id',
        'owner_id',
        'property_id',
    ];

    /** Pencari kos (Seeker) */
    public function seeker(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seeker_id');
    }

    /** Pemilik kos (Owner) */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /** Properti kos (sebagai konteks obrolan) */
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    /** Semua pesan dalam obrolan ini */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /** Mendapatkan partner chat (selain pengguna yang login) */
    public function getPartnerAttribute(): User
    {
        if (auth()->id() === $this->seeker_id) {
            return $this->owner;
        }
        return $this->seeker;
    }

    /** Mendapatkan jumlah pesan yang belum dibaca */
    public function getUnreadMessagesCountAttribute(): int
    {
        return $this->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->count();
    }
}
