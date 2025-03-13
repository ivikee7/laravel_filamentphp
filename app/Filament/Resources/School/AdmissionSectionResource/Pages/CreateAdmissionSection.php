<?php

namespace App\Filament\Resources\School\AdmissionSectionResource\Pages;

use App\Filament\Resources\School\AdmissionSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAdmissionSection extends CreateRecord
{
    protected static string $resource = AdmissionSectionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = auth()->id();
        $data['updater_id'] = auth()->id();

        return $data;
    }
}
