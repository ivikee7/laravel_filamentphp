<?php

namespace App\Filament\Admin\Resources\BloodGroups\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\BloodGroups\BloodGroupResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBloodGroup extends EditRecord
{
    protected static string $resource = BloodGroupResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
