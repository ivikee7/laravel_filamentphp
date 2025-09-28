<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\LibraryBookPublisher;
use App\Models\User;

class LibraryBookPublisherPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any LibraryBookPublisher');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('view LibraryBookPublisher');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create LibraryBookPublisher');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('update LibraryBookPublisher');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('delete LibraryBookPublisher');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any LibraryBookPublisher');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('restore LibraryBookPublisher');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any LibraryBookPublisher');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('replicate LibraryBookPublisher');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder LibraryBookPublisher');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, LibraryBookPublisher $librarybookpublisher): bool
    {
        return $user->checkPermissionTo('force-delete LibraryBookPublisher');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any LibraryBookPublisher');
    }
}
