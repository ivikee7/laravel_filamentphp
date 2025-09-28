<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\WhatsAppProvider;
use App\Models\User;

class WhatsAppProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WhatsAppProvider');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('view WhatsAppProvider');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WhatsAppProvider');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('update WhatsAppProvider');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('delete WhatsAppProvider');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any WhatsAppProvider');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('restore WhatsAppProvider');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any WhatsAppProvider');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('replicate WhatsAppProvider');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder WhatsAppProvider');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WhatsAppProvider $whatsappprovider): bool
    {
        return $user->checkPermissionTo('force-delete WhatsAppProvider');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any WhatsAppProvider');
    }
}
