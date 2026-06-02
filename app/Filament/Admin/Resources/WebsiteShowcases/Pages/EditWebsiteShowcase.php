<?php

namespace App\Filament\Admin\Resources\WebsiteShowcases\Pages;

use App\Filament\Admin\Resources\WebsiteShowcases\WebsiteShowcaseResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteShowcase extends EditRecord
{
    protected static string $resource = WebsiteShowcaseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
