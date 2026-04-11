<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Schemas;

use App\Models\TransportBus;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransportBusInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('registration_number'),
                TextEntry::make('model')
                    ->placeholder('-'),
                TextEntry::make('seating_capacity')
                    ->numeric(),
                TextEntry::make('driver_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('conductor_id')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('is_active')
                    ->boolean(),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (TransportBus $record): bool => $record->trashed()),
            ]);
    }
}
