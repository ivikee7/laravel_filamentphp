<?php

namespace App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages;

use App\Filament\Admin\Resources\Transport\TransportFuelLogs\TransportFuelLogResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportFuelLog extends ViewRecord
{
    protected static string $resource = TransportFuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
