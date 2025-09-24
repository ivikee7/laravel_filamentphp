<?php

namespace App\Filament\Admin\Resources\Roles\Resources\RoleHasPermissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;

class RoleHasPermissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
//                Select::make('permission_id')
//                    ->relationship('permissions', 'name')
//                    ->searchable()
//                    ->preload()
//                    ->required(),
                Select::make('permission_id') // <-- Changed from 'permission_id'
                ->relationship('permissions', 'name')
                    ->multiple() // <-- Added to allow selecting multiple permissions
                    ->searchable()
                    ->preload()
                    ->required(),
            ]);
    }
}
