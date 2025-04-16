<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SmsProvider;
use App\Models\User;

class SmsProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SmsProvider');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('view SmsProvider');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SmsProvider');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('update SmsProvider');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('delete SmsProvider');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SmsProvider');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('restore SmsProvider');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SmsProvider');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('replicate SmsProvider');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SmsProvider');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SmsProvider $smsprovider): bool
    {
        return $user->checkPermissionTo('force-delete SmsProvider');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SmsProvider');
    }
}
