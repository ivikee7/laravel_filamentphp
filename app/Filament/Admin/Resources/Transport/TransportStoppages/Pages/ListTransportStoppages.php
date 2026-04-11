<?php

namespace App\Filament\Admin\Resources\Transport\TransportStoppages\Pages;

use App\Filament\Admin\Resources\Transport\TransportStoppages\TransportStoppageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportStoppages extends ListRecords
{
    protected static string $resource = TransportStoppageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
