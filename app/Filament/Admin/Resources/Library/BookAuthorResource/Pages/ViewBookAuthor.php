<?php

namespace App\Filament\Admin\Resources\Library\BookAuthorResource\Pages;

use App\Filament\Admin\Resources\Library\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookAuthor extends ViewRecord
{
    protected static string $resource = BookAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
