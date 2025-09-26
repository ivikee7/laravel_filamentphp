<?php

namespace App\Filament\Admin\Resources\Roles\Schemas;

use App\Models\Permission;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('guard_name')
                    ->options([
                        'web' => 'Web',
                        'api' => 'Api',
                    ])
                    ->required(),
                Select::make('permissions')
                    ->relationship('permissions', 'name')
                    ->multiple()
                    ->preload()
                    ->searchable()
                    ->columnSpanFull(),
            ]);
    }
}
