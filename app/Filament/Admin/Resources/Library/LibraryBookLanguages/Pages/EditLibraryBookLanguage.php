<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookLanguages\LibraryBookLanguageResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLibraryBookLanguage extends EditRecord
{
    protected static string $resource = LibraryBookLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
