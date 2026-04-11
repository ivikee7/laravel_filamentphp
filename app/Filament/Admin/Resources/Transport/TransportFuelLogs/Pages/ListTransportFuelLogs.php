<?php

namespace App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages;

use App\Filament\Admin\Resources\Transport\TransportFuelLogs\TransportFuelLogResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportFuelLogs extends ListRecords
{
    protected static string $resource = TransportFuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
