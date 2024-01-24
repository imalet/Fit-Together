<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class VideoPolicy
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
    public function view(User $user, Video $video)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return $user->role->role === "ROLE_COACH";
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Video $video)
    {
        return $user->id === $video->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Video $video)
    {
        return $user->id === $video->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Video $video)
    {
        return $user->id === $video->user_id && $user->role->role === "ROLE_ADMIN"
            ? Response::allow()
            : Response::deny('Vous n\'avez pas le droit de mettre à jour cette Video.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Video $video)
    {
        return $user->role->role === "ROLE_ADMIN"
            ? Response::allow()
            : Response::deny('Vous n\'avez pas le droit de forcer la suppression de cette article cette Video.');
    }
}
