<?php

namespace App\Filament\Admin\Resources\Library\BookCategories\Pages;

use App\Filament\Admin\Resources\Library\BookCategories\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateBookCategory extends CreateRecord
{
    protected static string $resource = BookCategoryResource::class;
}
