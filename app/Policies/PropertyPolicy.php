<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;

class PropertyPolicy
{
    /**
     * Hanya owner dari properti ini yang boleh mengedit.
     */
    public function update(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    /**
     * Hanya owner dari properti ini yang boleh menghapus.
     */
    public function delete(User $user, Property $property): bool
    {
        return $user->id === $property->user_id;
    }

    /**
     * Hanya user dengan role owner yang boleh membuat properti baru.
     */
    public function create(User $user): bool
    {
        return $user->isOwner();
    }
}
