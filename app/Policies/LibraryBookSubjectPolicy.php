<?php

namespace App\Policies;

use App\Models\LibraryBookSubject;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LibraryBookSubjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookSubject');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookSubject $libraryBookSubject): bool
    {
        return $user->checkPermissionTo('view LibraryBookSubject');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookSubject');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookSubject $libraryBookSubject): bool
    {
        return $user->checkPermissionTo('update LibraryBookSubject');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookSubject $libraryBookSubject): bool
    {
        return $user->checkPermissionTo('delete LibraryBookSubject');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookSubject $libraryBookSubject): bool
    {
        return $user->checkPermissionTo('restore LibraryBookSubject');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookSubject $libraryBookSubject): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookSubject');
    }
}
