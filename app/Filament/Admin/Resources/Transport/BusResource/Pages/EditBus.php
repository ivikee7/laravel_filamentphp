<?php

namespace App\Filament\Admin\Resources\Transport\BusResource\Pages;

use App\Filament\Admin\Resources\Transport\BusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBus extends EditRecord
{
    protected static string $resource = BusResource::class;

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
