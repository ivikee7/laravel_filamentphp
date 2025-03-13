<?php

namespace App\Filament\Resources\School\AdmissionSectionResource\Pages;

use App\Filament\Resources\School\AdmissionSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionSections extends ListRecords
{
    protected static string $resource = AdmissionSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
