<?php

namespace App\Filament\Resources\School\AcadamicSessionResource\Pages;

use App\Filament\Resources\School\AcadamicSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAcadamicSession extends CreateRecord
{
    protected static string $resource = AcadamicSessionResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['creator_id'] = auth()->id();
        $data['updater_id'] = auth()->id();

        return $data;
    }
}
