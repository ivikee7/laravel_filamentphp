<?php

namespace App\Filament\Admin\Resources\Library\BookLocationResource\Pages;

use App\Filament\Admin\Resources\Library\BookLocationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookLocation extends EditRecord
{
    protected static string $resource = BookLocationResource::class;

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
