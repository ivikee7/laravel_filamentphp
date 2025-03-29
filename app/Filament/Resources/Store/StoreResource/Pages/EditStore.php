<?php

namespace App\Filament\Resources\Store\StoreResource\Pages;

use App\Filament\Resources\Store\StoreResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditStore extends EditRecord
{
    protected static string $resource = StoreResource::class;

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
