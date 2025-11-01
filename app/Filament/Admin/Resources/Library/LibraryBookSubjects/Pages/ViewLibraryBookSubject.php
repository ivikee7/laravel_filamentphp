<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookSubjects\LibraryBookSubjectResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLibraryBookSubject extends ViewRecord
{
    protected static string $resource = LibraryBookSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
