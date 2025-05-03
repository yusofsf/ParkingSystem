<?php

namespace App\Policies;

use App\Models\User;

class BookingPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user): bool
    {
        return $user->isManager();
    }

    /**
     * Determine whether the user can create models.
     */
    public function book(User $user): bool
    {
        return $user->isUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->isManager();
    }

    public function cancel(User $user): bool
    {
        return $user->isUser();
    }


    public function storeCreditCard(User $user): bool
    {
        return $user->isUser();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function storeCash(User $user): bool
    {
        return $user->isUser();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user)
    {
        // TODO
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
