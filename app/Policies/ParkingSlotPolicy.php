<?php

namespace App\Policies;

use App\Models\User;

class ParkingSlotPolicy
{
    public function update(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    public function view(User $user): bool
    {
        return $user->isManager() || $user->isAdmin();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user)
    {
        // TODO
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user)
    {
        // TODO
    }
}
