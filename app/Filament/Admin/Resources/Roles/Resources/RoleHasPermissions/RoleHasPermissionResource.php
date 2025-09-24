<?php

namespace App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions;

use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Pages\CreateRoleHasPermission;
use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Pages\EditRoleHasPermission;
use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Pages\ViewRoleHasPermission;
use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Schemas\RoleHasPermissionForm;
use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Schemas\RoleHasPermissionInfolist;
use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Tables\RoleHasPermissionsTable;
use App\Filament\Admin\Resources\Roles\RoleResource;
use App\Models\RoleHasPermission;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoleHasPermissionResource extends Resource
{
    protected static ?string $model = RoleHasPermission::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $parentResource = RoleResource::class;

    public static function form(Schema $schema): Schema
    {
        return RoleHasPermissionForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return RoleHasPermissionInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoleHasPermissionsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'create' => CreateRoleHasPermission::route('/create'),
            'view' => ViewRoleHasPermission::route('/{record}'),
            'edit' => EditRoleHasPermission::route('/{record}/edit'),
        ];
    }
}
