<?php

namespace App\Filament\Resources\School\AcadamicSessionResource\Pages;

use App\Filament\Resources\School\AcadamicSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAcadamicSession extends ViewRecord
{
    protected static string $resource = AcadamicSessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
