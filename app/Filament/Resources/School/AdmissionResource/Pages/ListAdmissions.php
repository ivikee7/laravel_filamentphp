<?php

namespace App\Filament\Resources\School\AdmissionResource\Pages;

use App\Filament\Resources\School\AdmissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListAdmissions extends ListRecords
{
    protected static string $resource = AdmissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
