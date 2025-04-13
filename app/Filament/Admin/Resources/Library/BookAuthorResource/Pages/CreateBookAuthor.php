<?php

namespace App\Filament\Admin\Resources\Library\BookAuthorResource\Pages;

use App\Filament\Admin\Resources\Library\BookAuthorResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookAuthor extends CreateRecord
{
    protected static string $resource = BookAuthorResource::class;
}
