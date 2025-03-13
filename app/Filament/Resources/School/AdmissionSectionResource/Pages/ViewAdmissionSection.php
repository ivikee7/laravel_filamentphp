<?php

namespace App\Filament\Resources\School\AdmissionSectionResource\Pages;

use App\Filament\Resources\School\AdmissionSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewAdmissionSection extends ViewRecord
{
    protected static string $resource = AdmissionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
