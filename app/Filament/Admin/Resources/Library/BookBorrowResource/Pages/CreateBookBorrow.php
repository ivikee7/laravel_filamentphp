<?php

namespace App\Filament\Admin\Resources\Library\BookBorrowResource\Pages;

use App\Filament\Admin\Resources\Library\BookBorrowResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookBorrow extends CreateRecord
{
    protected static string $resource = BookBorrowResource::class;
}
