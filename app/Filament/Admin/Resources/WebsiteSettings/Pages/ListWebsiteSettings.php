<?php

namespace App\Filament\Admin\Resources\WebsiteSettings\Pages;

use App\Filament\Admin\Resources\WebsiteSettings\WebsiteSettingsResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSettings extends ListRecords
{
    protected static string $resource = WebsiteSettingsResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
