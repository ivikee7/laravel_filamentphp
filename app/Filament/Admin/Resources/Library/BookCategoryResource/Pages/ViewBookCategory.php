<?php

namespace App\Filament\Admin\Resources\Library\BookCategoryResource\Pages;

use App\Filament\Admin\Resources\Library\BookCategoryResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewBookCategory extends ViewRecord
{
    protected static string $resource = BookCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
