<?php

namespace App\Filament\Admin\Resources\StudentClasses\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\StudentClasses\StudentClassResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditClasses extends EditRecord
{
    protected static string $resource = StudentClassResource::class;

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
