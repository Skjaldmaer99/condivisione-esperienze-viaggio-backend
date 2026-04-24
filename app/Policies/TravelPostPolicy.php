<?php

namespace App\Policies;

use App\Models\TravelPost;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TravelPostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TravelPost $travelPost): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TravelPost $travelPost): bool
    {
        return $user->id === $travelPost->used_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TravelPost $travelPost): bool
    {
        return $user->id === $travelPost->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TravelPost $travelPost): bool
    {
        return false; // non usato (oppure stessa logica di delete)
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TravelPost $travelPost): bool
    {
        return false; // non usato
    }
}
