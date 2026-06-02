<?php

namespace App\Filament\Admin\Resources\WebsiteCategories\Pages;

use App\Filament\Admin\Resources\WebsiteCategories\WebsiteCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteCategory extends EditRecord
{
    protected static string $resource = WebsiteCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
