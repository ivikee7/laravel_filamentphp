<?php

namespace App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Pages;

use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\RoleHasPermissionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditRoleHasPermission extends EditRecord
{
    protected static string $resource = RoleHasPermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
