<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
//    public function permissions(): BelongsToMany
//    {
//        // Explicitly define the pivot table if you're using Spatie's default
//        return $this->belongsToMany(
//            config('permission.models.permission'),
//            config('permission.table_names.role_has_permissions'),
//            'role_id',
//            'permission_id'
//        );
//    }
//    public function permissions(): BelongsToMany
//    {
//        return $this->belongsToMany(
//            config('permission.models.permission'),
//            'role_has_permissions'
//        );
//    }

//    public function roleHasPermissions(): BelongsToMany
//    {
//        // Explicitly define the pivot table if you're using Spatie's default
//        return $this->belongsToMany(
//            config('permission.models.permission'),
//            config('permission.table_names.role_has_permissions'),
//            'role_id',
//            'permission_id'
//        );
//    }
}
