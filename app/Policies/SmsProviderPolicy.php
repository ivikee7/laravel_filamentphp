<?php

namespace App\Policies;

use App\Models\SmsProvider;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SmsProviderPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any SmsProvider');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SmsProvider $smsProvider): bool
    {
        return $user->hasPermissionTo('view SmsProvider');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create SmsProvider');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SmsProvider $smsProvider): bool
    {
        return $user->hasPermissionTo('update SmsProvider');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SmsProvider $smsProvider): bool
    {
        return $user->hasPermissionTo('delete SmsProvider');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SmsProvider $smsProvider): bool
    {
        return $user->hasPermissionTo('restore SmsProvider');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SmsProvider $smsProvider): bool
    {
        return $user->hasPermissionTo('force-delete SmsProvider');
    }
}
