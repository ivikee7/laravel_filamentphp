<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogs\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Transport\FuelLogs\FuelLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFuelLogs extends ListRecords
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
