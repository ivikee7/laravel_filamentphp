<?php

namespace App\Filament\Admin\Resources\WebsiteTags\Pages;

use App\Filament\Admin\Resources\WebsiteTags\WebsiteTagResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteTags extends ListRecords
{
    protected static string $resource = WebsiteTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
