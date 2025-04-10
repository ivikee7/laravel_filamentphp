<?php

namespace App\Policies\IDCards;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ViewIDCardPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    use HandlesAuthorization;

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view IDCard');
    }

    public function markAttendance(User $user): bool
    {
        return $user->hasPermissionTo('create IDCard');
    }
}
