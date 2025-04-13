<?php

namespace App\Filament\Admin\Resources\Transport\BusResource\Pages;

use App\Filament\Admin\Resources\Transport\BusResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBus extends ViewRecord
{
    protected static string $resource = BusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
