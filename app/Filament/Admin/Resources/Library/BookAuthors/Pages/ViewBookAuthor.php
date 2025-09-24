<?php

namespace App\Filament\Admin\Resources\Library\BookAuthors\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookAuthors\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookAuthor extends ViewRecord
{
    protected static string $resource = BookAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
