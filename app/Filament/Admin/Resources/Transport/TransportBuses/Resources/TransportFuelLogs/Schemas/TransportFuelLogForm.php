<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class TransportFuelLogForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                DatePicker::make('date')
                    ->required(),
                TextInput::make('liters')
                    ->required()
                    ->numeric(),
                TextInput::make('cost')
                    ->required()
                    ->prefix('₹'),
                TextInput::make('filled_by')
                    ->default(null),
            ]);
    }
}
