<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LibraryBookAuthor;
use App\Models\User;

class LibraryBookAuthorPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookAuthor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('view LibraryBookAuthor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookAuthor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('update LibraryBookAuthor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('delete LibraryBookAuthor');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any LibraryBookAuthor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('restore LibraryBookAuthor');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any LibraryBookAuthor');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('replicate LibraryBookAuthor');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder LibraryBookAuthor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookAuthor $librarybookauthor): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookAuthor');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any LibraryBookAuthor');
    }
}
