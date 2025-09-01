<?php

namespace App\Filament\Admin\Resources\Transport\Buses\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Transport\Buses\BusResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBuses extends ListRecords
{
    protected static string $resource = BusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
