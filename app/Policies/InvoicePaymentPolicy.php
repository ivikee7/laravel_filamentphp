<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\InvoicePayment;
use App\Models\User;

class InvoicePaymentPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any InvoicePayment');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('view InvoicePayment');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create InvoicePayment');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('update InvoicePayment');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('delete InvoicePayment');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any InvoicePayment');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('restore InvoicePayment');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any InvoicePayment');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('replicate InvoicePayment');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder InvoicePayment');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, InvoicePayment $invoicepayment): bool
    {
        return $user->checkPermissionTo('force-delete InvoicePayment');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any InvoicePayment');
    }
}
