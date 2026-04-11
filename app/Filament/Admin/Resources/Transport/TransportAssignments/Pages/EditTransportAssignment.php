<?php

namespace App\Filament\Admin\Resources\Transport\TransportAssignments\Pages;

use App\Filament\Admin\Resources\Transport\TransportAssignments\TransportAssignmentResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditTransportAssignment extends EditRecord
{
    protected static string $resource = TransportAssignmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
