<?php

namespace App\Filament\Admin\Resources\Rooms\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RoomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('capacity')
                    ->required()
                    ->numeric(),
            ]);
    }
}
