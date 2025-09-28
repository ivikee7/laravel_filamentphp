<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\BloodGroup;
use App\Models\User;

class BloodGroupPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any BloodGroup');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('view BloodGroup');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create BloodGroup');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('update BloodGroup');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('delete BloodGroup');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any BloodGroup');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('restore BloodGroup');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any BloodGroup');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('replicate BloodGroup');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder BloodGroup');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, BloodGroup $bloodgroup): bool
    {
        return $user->checkPermissionTo('force-delete BloodGroup');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any BloodGroup');
    }
}
