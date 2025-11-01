<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookLanguages\LibraryBookLanguageResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLibraryBookLanguage extends ViewRecord
{
    protected static string $resource = LibraryBookLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
