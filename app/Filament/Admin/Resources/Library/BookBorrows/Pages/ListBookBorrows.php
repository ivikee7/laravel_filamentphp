<?php

namespace App\Filament\Admin\Resources\Library\BookBorrows\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookBorrows\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookBorrows extends ListRecords
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
