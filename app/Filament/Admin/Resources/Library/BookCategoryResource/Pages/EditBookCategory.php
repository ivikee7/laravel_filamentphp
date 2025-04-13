<?php

namespace App\Filament\Admin\Resources\Library\BookCategoryResource\Pages;

use App\Filament\Admin\Resources\Library\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookCategory extends EditRecord
{
    protected static string $resource = BookCategoryResource::class;

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
