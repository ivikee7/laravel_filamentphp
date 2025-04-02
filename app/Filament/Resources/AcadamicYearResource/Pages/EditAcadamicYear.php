<?php

namespace App\Filament\Resources\AcadamicYearResource\Pages;

use App\Filament\Resources\AcadamicYearResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAcadamicYear extends EditRecord
{
    protected static string $resource = AcadamicYearResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
