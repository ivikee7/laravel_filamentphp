<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\StudentSection;
use App\Models\User;

class StudentSectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StudentSection');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('view StudentSection');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StudentSection');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('update StudentSection');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('delete StudentSection');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any StudentSection');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('restore StudentSection');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any StudentSection');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('replicate StudentSection');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder StudentSection');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StudentSection $studentsection): bool
    {
        return $user->checkPermissionTo('force-delete StudentSection');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any StudentSection');
    }
}
