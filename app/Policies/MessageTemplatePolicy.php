<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\MessageTemplate;
use App\Models\User;

class MessageTemplatePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any MessageTemplate');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('view MessageTemplate');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create MessageTemplate');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('update MessageTemplate');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('delete MessageTemplate');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any MessageTemplate');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('restore MessageTemplate');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any MessageTemplate');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('replicate MessageTemplate');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder MessageTemplate');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, MessageTemplate $messagetemplate): bool
    {
        return $user->checkPermissionTo('force-delete MessageTemplate');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any MessageTemplate');
    }
}
