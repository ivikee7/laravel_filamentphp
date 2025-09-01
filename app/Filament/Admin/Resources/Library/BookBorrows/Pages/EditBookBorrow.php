<?php

namespace App\Filament\Admin\Resources\Library\BookBorrows\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\Library\BookBorrows\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBookBorrow extends EditRecord
{
    protected static string $resource = BookBorrowResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
