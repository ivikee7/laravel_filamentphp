<?php

namespace App\Filament\Admin\Resources\Transport\TransportFuelLogs\Pages;

use App\Filament\Admin\Resources\Transport\TransportFuelLogs\TransportFuelLogResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportFuelLog extends EditRecord
{
    protected static string $resource = TransportFuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
