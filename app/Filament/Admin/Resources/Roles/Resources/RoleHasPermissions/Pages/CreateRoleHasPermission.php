<?php

namespace App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Pages;

use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\RoleHasPermissionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoleHasPermission extends CreateRecord
{
    protected static string $resource = RoleHasPermissionResource::class;
}
