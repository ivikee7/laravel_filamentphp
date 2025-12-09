<?php

namespace App\Policies;

use App\Models\StoreInvoiceTransaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StoreInvoiceTransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StoreInvoiceTransection');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoreInvoiceTransaction $storeInvoiceTransection): bool
    {
        return $user->checkPermissionTo('view StoreInvoiceTransaction');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StoreInvoiceTransaction');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StoreInvoiceTransaction $storeInvoiceTransection): bool
    {
        return $user->checkPermissionTo('update StoreInvoiceTransaction');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StoreInvoiceTransaction $storeInvoiceTransection): bool
    {
        return $user->checkPermissionTo('delete StoreInvoiceTransaction');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StoreInvoiceTransaction $storeInvoiceTransection): bool
    {
        return $user->checkPermissionTo('restore StoreInvoiceTransaction');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StoreInvoiceTransaction $storeInvoiceTransection): bool
    {
        return $user->checkPermissionTo('force-delete StoreInvoiceTransaction');
    }
}
