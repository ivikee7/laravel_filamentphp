<?php

namespace App\Filament\Admin\Resources\WebsiteShowcases\Pages;

use App\Filament\Admin\Resources\WebsiteShowcases\WebsiteShowcaseResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteShowcases extends ListRecords
{
    protected static string $resource = WebsiteShowcaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
