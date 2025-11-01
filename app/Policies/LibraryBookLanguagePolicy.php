<?php

namespace App\Policies;

use App\Models\LibraryBookLanguage;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class LibraryBookLanguagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookLanguage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookLanguage $libraryBookLanguage): bool
    {
        return $user->checkPermissionTo('view LibraryBookLanguage');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookLanguage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookLanguage $libraryBookLanguage): bool
    {
        return $user->checkPermissionTo('update LibraryBookLanguage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookLanguage $libraryBookLanguage): bool
    {
        return $user->checkPermissionTo('delete LibraryBookLanguage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookLanguage $libraryBookLanguage): bool
    {
        return $user->checkPermissionTo('restore LibraryBookLanguage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookLanguage $libraryBookLanguage): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookLanguage');
    }
}
