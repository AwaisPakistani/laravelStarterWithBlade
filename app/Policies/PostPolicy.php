<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\{Post, User};

class PostPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Post $post): bool
    {
        return $user->id===$post->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user,$Userid): bool
    {
        return $user->id===$Userid;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Post $Post): bool
    {
        return $user->id===$Post->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Post $Post): bool
    {
        return $user->id===$Post->created_by;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Post $post): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Post $post): bool
    {
        return false;
    }
    public function before(User $user): bool|null
    {
       return $user->hasRole('Super Admin')?true:null;
    }
}
