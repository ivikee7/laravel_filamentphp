<?php

namespace App\Filament\Admin\Resources\WebsitePages\Pages;

use App\Filament\Admin\Resources\WebsitePages\WebsitePageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsitePages extends ListRecords
{
    protected static string $resource = WebsitePageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
