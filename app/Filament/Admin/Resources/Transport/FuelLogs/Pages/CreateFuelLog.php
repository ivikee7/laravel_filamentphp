<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogs\Pages;

use App\Filament\Admin\Resources\Transport\FuelLogs\FuelLogResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateFuelLog extends CreateRecord
{
    protected static string $resource = FuelLogResource::class;
}
