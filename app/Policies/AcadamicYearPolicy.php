<?php

namespace App\Policies;

use App\Models\AcadamicYear;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AcadamicYearPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any AcadamicYear');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, AcadamicYear $acadamicYear): bool
    {
        return $user->hasPermissionTo('view AcadamicYear');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create AcadamicYear');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AcadamicYear $acadamicYear): bool
    {
        return $user->hasPermissionTo('update AcadamicYear');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AcadamicYear $acadamicYear): bool
    {
        return $user->hasPermissionTo('delete AcadamicYear');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AcadamicYear $acadamicYear): bool
    {
        return $user->hasPermissionTo('restore AcadamicYear');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AcadamicYear $acadamicYear): bool
    {
        return $user->hasPermissionTo('force-delete AcadamicYear');
    }
}
