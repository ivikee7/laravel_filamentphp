<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\Schemas;

use App\Models\TransportFuelLog;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class TransportFuelLogInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('bus_id')
                    ->numeric(),
                TextEntry::make('date')
                    ->date(),
                TextEntry::make('liters')
                    ->numeric(),
                TextEntry::make('cost')
                    ->money(),
                TextEntry::make('filled_by')
                    ->placeholder('-'),
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
                    ->visible(fn (TransportFuelLog $record): bool => $record->trashed()),
            ]);
    }
}
