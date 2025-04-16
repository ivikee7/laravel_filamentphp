<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TransportStoppage;
use App\Models\User;

class TransportStoppagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TransportStoppage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('view TransportStoppage');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TransportStoppage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('update TransportStoppage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('delete TransportStoppage');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TransportStoppage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('restore TransportStoppage');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TransportStoppage');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('replicate TransportStoppage');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TransportStoppage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransportStoppage $transportstoppage): bool
    {
        return $user->checkPermissionTo('force-delete TransportStoppage');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TransportStoppage');
    }
}
