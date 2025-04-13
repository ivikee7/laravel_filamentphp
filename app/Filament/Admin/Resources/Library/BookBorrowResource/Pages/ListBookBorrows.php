<?php

namespace App\Filament\Admin\Resources\Library\BookBorrowResource\Pages;

use App\Filament\Admin\Resources\Library\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookBorrows extends ListRecords
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
