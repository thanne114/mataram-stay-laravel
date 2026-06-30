<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Hanya owner dari properti ini atau admin yang boleh menghapus.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id || $user->isAdmin();
    }

    /**
     * Hanya user dengan role owner yang boleh membuat properti baru.
     */
    public function create(User $user): bool
    {
        return $user->isOwner();
    }
}
