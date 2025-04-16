<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\GSuiteUser;
use App\Models\User;

class GSuiteUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any GSuiteUser');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('view GSuiteUser');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create GSuiteUser');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('update GSuiteUser');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('delete GSuiteUser');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any GSuiteUser');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('restore GSuiteUser');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any GSuiteUser');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('replicate GSuiteUser');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder GSuiteUser');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GSuiteUser $gsuiteuser): bool
    {
        return $user->checkPermissionTo('force-delete GSuiteUser');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any GSuiteUser');
    }
}
