<?php

namespace App\Filament\Admin\Resources\Transport\FuelLogs\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Transport\FuelLogs\FuelLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditFuelLog extends EditRecord
{
    protected static string $resource = FuelLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
