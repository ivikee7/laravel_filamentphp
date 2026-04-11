<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Pages;

use App\Filament\Admin\Resources\Transport\TransportAssignments\TransportAssignmentResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewTransportAssignment extends ViewRecord
{
    protected static string $resource = TransportAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
