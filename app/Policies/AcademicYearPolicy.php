<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\AcademicYear;
use App\Models\User;

class AcademicYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any AcademicYear');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('view AcademicYear');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create AcademicYear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('update AcademicYear');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('delete AcademicYear');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any AcademicYear');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('restore AcademicYear');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any AcademicYear');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('replicate AcademicYear');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder AcademicYear');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcademicYear $academicyear): bool
    {
        return $user->checkPermissionTo('force-delete AcademicYear');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any AcademicYear');
    }
}
