<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Pages;

use App\Filament\Admin\Resources\Transport\TransportAssignments\TransportAssignmentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTransportAssignments extends ListRecords
{
    protected static string $resource = TransportAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
