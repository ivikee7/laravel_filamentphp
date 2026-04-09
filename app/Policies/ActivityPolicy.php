<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use Spatie\Activitylog\Models\Activity;

class ActivityPolicy
{
    /**
     * Determine whether the user can view any activity logs.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Activity');
    }

    /**
     * Determine whether the user can view the activity log.
     */
    public function view(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('view Activity');
    }

    /**
     * Determine whether the user can create activity logs.
     * (usually not required for activity logs but provided for completeness)
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Activity');
    }

    /**
     * Determine whether the user can update the activity log.
     */
    public function update(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('update Activity');
    }

    /**
     * Determine whether the user can delete the activity log.
     */
    public function delete(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('delete Activity');
    }

    /**
     * Determine whether the user can delete any activity logs.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Activity');
    }

    /**
     * Determine whether the user can restore the activity log.
     */
    public function restore(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('restore Activity');
    }

    /**
     * Determine whether the user can restore any activity logs.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Activity');
    }

    /**
     * Determine whether the user can replicate the activity log.
     */
    public function replicate(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('replicate Activity');
    }

    /**
     * Determine whether the user can reorder the activity logs.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Activity');
    }

    /**
     * Determine whether the user can permanently delete the activity log.
     */
    public function forceDelete(User $user, Activity $activity): bool
    {
        return $user->checkPermissionTo('force-delete Activity');
    }

    /**
     * Determine whether the user can permanently delete any activity logs.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Activity');
    }
}

