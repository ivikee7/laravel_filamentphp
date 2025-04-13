<?php

namespace App\Filament\Admin\Resources\Library\BookCategoryResource\Pages;

use App\Filament\Admin\Resources\Library\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookCategory extends CreateRecord
{
    protected static string $resource = BookCategoryResource::class;
}
