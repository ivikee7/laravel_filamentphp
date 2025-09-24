<?php

namespace App\Filament\Admin\Resources\Library\BookAuthors\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookAuthors\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookAuthors extends ListRecords
{
    protected static string $resource = BookAuthorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
