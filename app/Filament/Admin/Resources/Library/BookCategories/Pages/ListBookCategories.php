<?php

namespace App\Filament\Admin\Resources\Library\BookCategories\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\Library\BookCategories\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookCategories extends ListRecords
{
    protected static string $resource = BookCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
