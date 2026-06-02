<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Pages;

use App\Filament\Admin\Resources\WebsiteMenus\WebsiteMenuResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteMenus extends ListRecords
{
    protected static string $resource = WebsiteMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
