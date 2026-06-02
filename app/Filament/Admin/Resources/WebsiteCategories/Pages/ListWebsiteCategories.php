<?php

namespace App\Filament\Admin\Resources\WebsiteCategories\Pages;

use App\Filament\Admin\Resources\WebsiteCategories\WebsiteCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteCategories extends ListRecords
{
    protected static string $resource = WebsiteCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
