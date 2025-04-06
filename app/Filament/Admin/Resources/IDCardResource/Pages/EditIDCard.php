<?php

namespace App\Filament\Admin\Resources\IDCardResource\Pages;

use App\Filament\Admin\Resources\IDCardResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditIDCard extends EditRecord
{
    protected static string $resource = IDCardResource::class;

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
