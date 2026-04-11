<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\RelationManagers;

use App\Filament\Admin\Resources\Transport\TransportBuses\Resources\TransportFuelLogs\TransportFuelLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Table;

class TransportFuelLogsRelationManager extends RelationManager
{
    protected static string $relationship = 'transportFuelLogs';

    protected static ?string $relatedResource = TransportFuelLogResource::class;

    public function table(Table $table): Table
    {
        return $table
            ->headerActions([
                CreateAction::make(),
            ]);
    }
}
