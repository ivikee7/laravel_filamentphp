<?php

namespace App\Filament\Admin\Resources\StoppageResource\Pages;

use App\Filament\Admin\Resources\StoppageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStoppage extends EditRecord
{
    protected static string $resource = StoppageResource::class;

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
