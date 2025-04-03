<?php

namespace App\Policies;

use App\Models\Classes;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ClassesPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any Classes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Classes $classes): bool
    {
        return $user->hasPermissionTo('view Classes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create Classes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Classes $classes): bool
    {
        return $user->hasPermissionTo('update Classes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Classes $classes): bool
    {
        return $user->hasPermissionTo('delete Classes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Classes $classes): bool
    {
        return $user->hasPermissionTo('restore Classes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Classes $classes): bool
    {
        return $user->hasPermissionTo('force-delete Classes');
    }
}
