<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookSubjects\LibraryBookSubjectResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListLibraryBookSubjects extends ListRecords
{
    protected static string $resource = LibraryBookSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
