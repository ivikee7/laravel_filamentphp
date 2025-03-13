<?php

namespace App\Filament\Resources\School\AcadamicSessionResource\Pages;

use App\Filament\Resources\School\AcadamicSessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcadamicSession extends EditRecord
{
    protected static string $resource = AcadamicSessionResource::class;

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
