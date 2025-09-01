<?php

namespace App\Filament\Admin\Resources\Library\BookBorrows\Pages;

use App\Filament\Admin\Resources\Library\BookBorrows\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookBorrow extends CreateRecord
{
    protected static string $resource = BookBorrowResource::class;
}
