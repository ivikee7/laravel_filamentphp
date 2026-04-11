<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Pages;

use App\Filament\Admin\Resources\Transport\TransportBuses\TransportBusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportBuses extends ListRecords
{
    protected static string $resource = TransportBusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
