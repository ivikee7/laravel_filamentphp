<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogs\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Transport\FuelLogs\FuelLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFuelLog extends ViewRecord
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
