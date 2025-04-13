<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogResource\Pages;

use App\Filament\Admin\Resources\Transport\FuelLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewFuelLog extends ViewRecord
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
