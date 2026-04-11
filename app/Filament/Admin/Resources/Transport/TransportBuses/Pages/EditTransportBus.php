<?php

namespace App\Filament\Admin\Resources\Transport\TransportBuses\Pages;

use App\Filament\Admin\Resources\Transport\TransportBuses\TransportBusResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportBus extends EditRecord
{
    protected static string $resource = TransportBusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
