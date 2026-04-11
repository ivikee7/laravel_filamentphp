<?php

namespace App\Filament\Admin\Resources\Transport\TransportRoutes\Pages;

use App\Filament\Admin\Resources\Transport\TransportRoutes\TransportRouteResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportRoute extends EditRecord
{
    protected static string $resource = TransportRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
