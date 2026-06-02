<?php

namespace App\Filament\Admin\Resources\WebsiteTags\Pages;

use App\Filament\Admin\Resources\WebsiteTags\WebsiteTagResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteTag extends EditRecord
{
    protected static string $resource = WebsiteTagResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
