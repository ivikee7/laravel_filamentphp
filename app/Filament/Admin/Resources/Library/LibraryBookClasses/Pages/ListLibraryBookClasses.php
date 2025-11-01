<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookClasses\LibraryBookClassResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLibraryBookClasses extends ListRecords
{
    protected static string $resource = LibraryBookClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
