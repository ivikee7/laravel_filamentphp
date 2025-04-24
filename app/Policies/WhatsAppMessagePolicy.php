<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\WhatsAppMessage;
use App\Models\User;

class WhatsAppMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WhatsAppMessage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('view WhatsAppMessage');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WhatsAppMessage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('update WhatsAppMessage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('delete WhatsAppMessage');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any WhatsAppMessage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('restore WhatsAppMessage');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any WhatsAppMessage');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('replicate WhatsAppMessage');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder WhatsAppMessage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WhatsAppMessage $whatsappmessage): bool
    {
        return $user->checkPermissionTo('force-delete WhatsAppMessage');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any WhatsAppMessage');
    }
}
