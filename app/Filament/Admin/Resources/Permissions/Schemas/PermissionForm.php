<?php

namespace App\Filament\Admin\Resources\Permissions\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PermissionForm
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
            ]);
    }
}
