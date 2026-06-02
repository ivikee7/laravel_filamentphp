<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Pages;

use App\Filament\Admin\Resources\WebsiteMenus\WebsiteMenuResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewWebsiteMenu extends ViewRecord
{
    protected static string $resource = WebsiteMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
