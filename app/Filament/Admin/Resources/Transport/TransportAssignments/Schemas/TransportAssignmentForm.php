<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransportAssignmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('route_id')
                    ->required()
                    ->numeric(),
                TextInput::make('stoppage_id')
                    ->numeric()
                    ->default(null),
                TextInput::make('bus_id')
                    ->numeric()
                    ->default(null),
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
