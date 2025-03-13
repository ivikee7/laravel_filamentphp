<?php

namespace App\Filament\Resources\School\AdmissionClassResource\Pages;

use App\Filament\Resources\School\AdmissionClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmissionClasses extends ListRecords
{
    protected static string $resource = AdmissionClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
