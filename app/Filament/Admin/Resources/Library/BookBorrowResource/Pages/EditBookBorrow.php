<?php

namespace App\Filament\Admin\Resources\Library\BookBorrowResource\Pages;

use App\Filament\Admin\Resources\Library\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookBorrow extends EditRecord
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
