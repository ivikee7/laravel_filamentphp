<?php

namespace App\Policies;

use App\Models\StoreInvoiceItem;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StoreInvoiceItemPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any StoreInvoiceItem');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StoreInvoiceItem $storeInvoiceItem): bool
    {
        return $user->checkPermissionTo('view StoreInvoiceItem');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create StoreInvoiceItem');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StoreInvoiceItem $storeInvoiceItem): bool
    {
        return $user->checkPermissionTo('update StoreInvoiceItem');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StoreInvoiceItem $storeInvoiceItem): bool
    {
        return $user->checkPermissionTo('delete StoreInvoiceItem');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StoreInvoiceItem $storeInvoiceItem): bool
    {
        return $user->checkPermissionTo('restore StoreInvoiceItem');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StoreInvoiceItem $storeInvoiceItem): bool
    {
        return $user->checkPermissionTo('force-delete StoreInvoiceItem');
    }
}
