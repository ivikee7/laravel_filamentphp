<?php

namespace App\Filament\Admin\Resources\Transport\Buses\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Transport\Buses\BusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBus extends ViewRecord
{
    protected static string $resource = BusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
