<?php

namespace App\Filament\Admin\Resources\Library\LibraryBookClasses\Pages;

use App\Filament\Admin\Resources\Library\LibraryBookClasses\LibraryBookClassResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewLibraryBookClass extends ViewRecord
{
    protected static string $resource = LibraryBookClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
