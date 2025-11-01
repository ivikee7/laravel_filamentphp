<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookClasses\LibraryBookClassResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditLibraryBookClass extends EditRecord
{
    protected static string $resource = LibraryBookClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
