<?php

namespace App\Policies;

use App\Models\LibraryBookClass;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LibraryBookClassPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookClass');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookClass $libraryBookClass): bool
    {
        return $user->checkPermissionTo('view LibraryBookClass');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookClass');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookClass $libraryBookClass): bool
    {
        return $user->checkPermissionTo('update LibraryBookClass');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookClass $libraryBookClass): bool
    {
        return $user->checkPermissionTo('delete LibraryBookClass');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookClass $libraryBookClass): bool
    {
        return $user->checkPermissionTo('restore LibraryBookClass');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookClass $libraryBookClass): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookClass');
    }
}
