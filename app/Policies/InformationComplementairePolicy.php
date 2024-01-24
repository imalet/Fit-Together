<?php

namespace App\Policies;

use App\Models\InformationComplementaire;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class InformationComplementairePolicy
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
    public function view(User $user, InformationComplementaire $informationComplementaire): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    // public function create(User $user): bool
    // {
    //     return $user->role->role === "ROLE_COACH";
    // }

    public function create(User $user): bool
    {
        return $user->role->role === "ROLE_COACH";
    }



    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InformationComplementaire $informationComplementaire)
    {
        return $user->id === $informationComplementaire->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InformationComplementaire $informationComplementaire)
    {
        return $user->id === $informationComplementaire->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InformationComplementaire $informationComplementaire)
    {
        return $user->id === $informationComplementaire->user_id && $user->role->role === "ROLE_ADMIN"
            ? Response::allow()
            : Response::deny('Vous n\'avez pas le droit de mettre à jour ces information.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user)
    {
        return $user->role->role === "ROLE_ADMIN"
            ? Response::allow()
            : Response::deny('Vous n\'avez pas le droit de forcer la suppression de cette article ces informations.');
    }
}
