<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\Pages;

use App\Filament\Admin\Resources\WebsiteMenus\Resources\WebsiteMenuItems\WebsiteMenuItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsiteMenuItem extends CreateRecord
{
    protected static string $resource = WebsiteMenuItemResource::class;
}
