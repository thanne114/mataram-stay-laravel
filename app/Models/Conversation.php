<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    /** Pesan terakhir dalam obrolan ini */
    public function latestMessage(): HasOne
    {
        return $this->hasOne(Message::class)->latestOfMany();
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
        if (array_key_exists('unread_messages_count', $this->attributes)) {
            return (int) $this->attributes['unread_messages_count'];
        }

        return $this->messages()
            ->where('sender_id', '!=', auth()->id())
            ->where('is_read', false)
            ->count();
    }

    /** Scope to load conversations for a specific user with required relations and unread counts */
    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
                $q->where('seeker_id', $userId)
                  ->orWhere('owner_id', $userId);
            })
            ->with([
                'seeker',
                'owner',
                'property',
                'latestMessage'
            ])
            ->withCount([
                'messages as unread_messages_count' => function ($q) use ($userId) {
                    $q->where('sender_id', '!=', $userId)
                      ->where('is_read', false);
                }
            ]);
    }
}
