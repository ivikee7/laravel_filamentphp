<?php

namespace App\Policies\IDCards;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ListIDCardsPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    use HandlesAuthorization;

    public static function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-any Attendance');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('view Attendance');
    }
}
