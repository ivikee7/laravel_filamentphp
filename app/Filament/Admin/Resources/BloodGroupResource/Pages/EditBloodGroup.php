<?php

namespace App\Filament\Admin\Resources\BloodGroupResource\Pages;

use App\Filament\Admin\Resources\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodGroup extends EditRecord
{
    protected static string $resource = BloodGroupResource::class;

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
