<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TransportAssignment;
use App\Models\User;

class TransportAssignmentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TransportAssignment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('view TransportAssignment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TransportAssignment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('update TransportAssignment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('delete TransportAssignment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TransportAssignment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('restore TransportAssignment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TransportAssignment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('replicate TransportAssignment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TransportAssignment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransportAssignment $transportassignment): bool
    {
        return $user->checkPermissionTo('force-delete TransportAssignment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TransportAssignment');
    }
}
