<?php

namespace App\Filament\Resources\School\AdmissionClassResource\Pages;

use App\Filament\Resources\School\AdmissionClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdmissionClass extends EditRecord
{
    protected static string $resource = AdmissionClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updater_id'] = auth()->id();
        return $data;
    }
}
