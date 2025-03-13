<?php

namespace App\Filament\Resources\School\AdmissionSectionResource\Pages;

use App\Filament\Resources\School\AdmissionSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdmissionSection extends EditRecord
{
    protected static string $resource = AdmissionSectionResource::class;

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
