<?php

namespace App\Filament\Admin\Resources\Roles\RelationManagers;

use App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\RoleHasPermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class PermissionsRelationManager extends RelationManager
{
    protected static string $relationship = 'permissions';

    protected static ?string $relatedResource = RoleHasPermissionResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
