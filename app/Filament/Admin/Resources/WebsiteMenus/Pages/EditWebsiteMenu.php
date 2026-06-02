<?php

namespace App\Filament\Admin\Resources\WebsiteMenus\Pages;

use App\Filament\Admin\Resources\WebsiteMenus\WebsiteMenuResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteMenu extends EditRecord
{
    protected static string $resource = WebsiteMenuResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
