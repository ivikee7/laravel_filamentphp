<?php

namespace App\Policies;

use App\Models\StoreCart;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StoreCartPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StoreCart');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoreCart $storeCart): bool
    {
        return $user->checkPermissionTo('view StoreCart');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StoreCart');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StoreCart $storeCart): bool
    {
        return $user->checkPermissionTo('update StoreCart');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StoreCart $storeCart): bool
    {
        return $user->checkPermissionTo('delete StoreCart');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StoreCart $storeCart): bool
    {
        return $user->checkPermissionTo('restore StoreCart');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StoreCart $storeCart): bool
    {
        return $user->checkPermissionTo('force-delete StoreCart');
    }
}
