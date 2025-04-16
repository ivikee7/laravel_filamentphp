<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\UserFinancialDetail;
use App\Models\User;

class UserFinancialDetailPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UserFinancialDetail');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('view UserFinancialDetail');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UserFinancialDetail');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('update UserFinancialDetail');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('delete UserFinancialDetail');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any UserFinancialDetail');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('restore UserFinancialDetail');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any UserFinancialDetail');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('replicate UserFinancialDetail');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder UserFinancialDetail');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UserFinancialDetail $userfinancialdetail): bool
    {
        return $user->checkPermissionTo('force-delete UserFinancialDetail');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any UserFinancialDetail');
    }
}
