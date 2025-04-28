<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ClassName;
use App\Models\User;

class ClassNamePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ClassName');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('view ClassName');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ClassName');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('update ClassName');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('delete ClassName');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ClassName');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('restore ClassName');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ClassName');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('replicate ClassName');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ClassName');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ClassName $classname): bool
    {
        return $user->checkPermissionTo('force-delete ClassName');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ClassName');
    }
}
