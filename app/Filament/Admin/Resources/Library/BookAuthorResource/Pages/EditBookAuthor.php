<?php

namespace App\Filament\Admin\Resources\Library\BookAuthorResource\Pages;

use App\Filament\Admin\Resources\Library\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookAuthor extends EditRecord
{
    protected static string $resource = BookAuthorResource::class;

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
