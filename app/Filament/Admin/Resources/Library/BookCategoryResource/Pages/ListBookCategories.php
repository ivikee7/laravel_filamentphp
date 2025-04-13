<?php

namespace App\Filament\Admin\Resources\Library\BookCategoryResource\Pages;

use App\Filament\Admin\Resources\Library\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookCategories extends ListRecords
{
    protected static string $resource = BookCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
