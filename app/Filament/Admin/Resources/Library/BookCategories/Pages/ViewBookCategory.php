<?php

namespace App\Filament\Admin\Resources\Library\BookCategories\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\Library\BookCategories\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookCategory extends ViewRecord
{
    protected static string $resource = BookCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
