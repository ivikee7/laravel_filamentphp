<?php

namespace App\Policies;

use App\Models\StoreInvoice;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StoreInvoicePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StoreInvoice');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('view StoreInvoice');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StoreInvoice');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('update StoreInvoice');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('delete StoreInvoice');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('restore StoreInvoice');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('force-delete StoreInvoice');
    }

    public function discount(User $user, StoreInvoice $storeInvoice): bool
    {
        return $user->checkPermissionTo('discount StoreInvoice');
    }
}
