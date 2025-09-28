<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LibraryBookLocation;
use App\Models\User;

class LibraryBookLocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookLocation');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('view LibraryBookLocation');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookLocation');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('update LibraryBookLocation');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('delete LibraryBookLocation');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any LibraryBookLocation');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('restore LibraryBookLocation');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any LibraryBookLocation');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('replicate LibraryBookLocation');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder LibraryBookLocation');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookLocation $librarybooklocation): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookLocation');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any LibraryBookLocation');
    }
}
