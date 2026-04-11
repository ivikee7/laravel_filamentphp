<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\Pages;

use App\Filament\Admin\Resources\Transport\TransportRoutes\TransportRouteResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportRoute extends ViewRecord
{
    protected static string $resource = TransportRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
