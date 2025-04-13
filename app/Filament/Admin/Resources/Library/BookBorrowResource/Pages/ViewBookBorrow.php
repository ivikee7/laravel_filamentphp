<?php

namespace App\Filament\Admin\Resources\Library\BookBorrowResource\Pages;

use App\Filament\Admin\Resources\Library\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookBorrow extends ViewRecord
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
