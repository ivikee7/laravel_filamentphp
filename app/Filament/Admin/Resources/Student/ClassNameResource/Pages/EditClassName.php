<?php

namespace App\Filament\Admin\Resources\Student\ClassNameResource\Pages;

use App\Filament\Admin\Resources\Student\ClassNameResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClassName extends EditRecord
{
    protected static string $resource = ClassNameResource::class;

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
