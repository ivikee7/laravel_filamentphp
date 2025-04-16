<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\WebsiteEnquiry;
use App\Models\User;

class WebsiteEnquiryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any WebsiteEnquiry');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('view WebsiteEnquiry');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create WebsiteEnquiry');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('update WebsiteEnquiry');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('delete WebsiteEnquiry');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any WebsiteEnquiry');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('restore WebsiteEnquiry');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any WebsiteEnquiry');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('replicate WebsiteEnquiry');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder WebsiteEnquiry');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WebsiteEnquiry $websiteenquiry): bool
    {
        return $user->checkPermissionTo('force-delete WebsiteEnquiry');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any WebsiteEnquiry');
    }
}
