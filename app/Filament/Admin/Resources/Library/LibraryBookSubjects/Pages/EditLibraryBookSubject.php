<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookSubjects\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookSubjects\LibraryBookSubjectResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLibraryBookSubject extends EditRecord
{
    protected static string $resource = LibraryBookSubjectResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
