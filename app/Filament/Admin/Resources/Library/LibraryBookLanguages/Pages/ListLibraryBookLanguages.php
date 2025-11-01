<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookLanguages\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookLanguages\LibraryBookLanguageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLibraryBookLanguages extends ListRecords
{
    protected static string $resource = LibraryBookLanguageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
