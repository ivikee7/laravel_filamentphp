<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentClass;
use App\Models\User;

class StudentClassPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StudentClass');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('view StudentClass');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StudentClass');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('update StudentClass');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('delete StudentClass');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any StudentClass');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('restore StudentClass');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any StudentClass');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('replicate StudentClass');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder StudentClass');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentClass $studentclass): bool
    {
        return $user->checkPermissionTo('force-delete StudentClass');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any StudentClass');
    }
}
