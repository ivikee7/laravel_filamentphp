<?php

namespace App\Filament\Admin\Resources\Transport\TransportStoppages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TransportStoppageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('route_id')
                    ->required()
                    ->numeric(),
                TextInput::make('location')
                    ->default(null),
                TextInput::make('order')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->required(),
                TextInput::make('created_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('updated_by')
                    ->numeric()
                    ->default(null),
                TextInput::make('deleted_by')
                    ->numeric()
                    ->default(null),
            ]);
    }
}
