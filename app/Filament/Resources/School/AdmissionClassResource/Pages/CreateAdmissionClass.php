<?php

namespace App\Filament\Resources\School\AdmissionClassResource\Pages;

use App\Filament\Resources\School\AdmissionClassResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmissionClass extends CreateRecord
{
    protected static string $resource = AdmissionClassResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = auth()->id();
        $data['updater_id'] = auth()->id();

        return $data;
    }
}
