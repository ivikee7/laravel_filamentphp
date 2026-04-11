<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\Pages;

use App\Filament\Admin\Resources\Transport\TransportRoutes\TransportRouteResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportRoutes extends ListRecords
{
    protected static string $resource = TransportRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
