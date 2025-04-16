<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TransportRoute;
use App\Models\User;

class TransportRoutePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TransportRoute');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('view TransportRoute');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TransportRoute');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('update TransportRoute');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('delete TransportRoute');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TransportRoute');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('restore TransportRoute');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TransportRoute');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('replicate TransportRoute');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TransportRoute');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TransportRoute $transportroute): bool
    {
        return $user->checkPermissionTo('force-delete TransportRoute');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TransportRoute');
    }
}
