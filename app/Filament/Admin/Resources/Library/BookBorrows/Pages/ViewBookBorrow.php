<?php

namespace App\Filament\Admin\Resources\Library\BookBorrows\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookBorrows\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookBorrow extends ViewRecord
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
