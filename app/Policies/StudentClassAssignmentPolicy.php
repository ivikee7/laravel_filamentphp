<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentClassAssignment;
use App\Models\User;

class StudentClassAssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StudentClassAssignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('view StudentClassAssignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StudentClassAssignment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('update StudentClassAssignment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('delete StudentClassAssignment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any StudentClassAssignment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('restore StudentClassAssignment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any StudentClassAssignment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('replicate StudentClassAssignment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder StudentClassAssignment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentClassAssignment $studentclassassignment): bool
    {
        return $user->checkPermissionTo('force-delete StudentClassAssignment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any StudentClassAssignment');
    }
}
