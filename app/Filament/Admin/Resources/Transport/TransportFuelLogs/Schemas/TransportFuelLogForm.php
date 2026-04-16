<?php

namespace App\Filament\Admin\Resources\Transport\TransportFuelLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransportFuelLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bus_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('date')
                    ->required(),
                TextInput::make('liters')
                    ->required()
                    ->numeric(),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->prefix('₹'),
                TextInput::make('filled_by')
                    ->default(null),
            ]);
    }
}
