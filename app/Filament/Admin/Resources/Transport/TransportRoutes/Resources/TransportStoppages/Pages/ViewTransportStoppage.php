<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\Pages;

use App\Filament\Admin\Resources\Transport\TransportRoutes\Resources\TransportStoppages\TransportStoppageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportStoppage extends ViewRecord
{
    protected static string $resource = TransportStoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
