<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Quota;
use App\Models\User;

class QuotaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Quota');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('view Quota');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Quota');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('update Quota');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('delete Quota');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Quota');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('restore Quota');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Quota');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('replicate Quota');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Quota');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Quota $quota): bool
    {
        return $user->checkPermissionTo('force-delete Quota');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Quota');
    }
}
