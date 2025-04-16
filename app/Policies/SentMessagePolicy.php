<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\SentMessage;
use App\Models\User;

class SentMessagePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SentMessage');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('view SentMessage');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SentMessage');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('update SentMessage');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('delete SentMessage');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SentMessage');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('restore SentMessage');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SentMessage');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('replicate SentMessage');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SentMessage');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, SentMessage $sentmessage): bool
    {
        return $user->checkPermissionTo('force-delete SentMessage');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SentMessage');
    }
}
