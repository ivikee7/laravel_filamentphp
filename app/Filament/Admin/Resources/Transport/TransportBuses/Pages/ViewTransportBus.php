<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Pages;

use App\Filament\Admin\Resources\Transport\TransportBuses\TransportBusResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportBus extends ViewRecord
{
    protected static string $resource = TransportBusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
