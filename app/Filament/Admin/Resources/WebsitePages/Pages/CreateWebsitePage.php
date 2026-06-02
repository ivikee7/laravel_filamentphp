<?php

namespace App\Filament\Admin\Resources\WebsitePages\Pages;

use App\Filament\Admin\Resources\WebsitePages\WebsitePageResource;
use App\Filament\Admin\Resources\WebsitePages\Support\WebsitePageBuilder;
use Filament\Resources\Pages\CreateRecord;

class CreateWebsitePage extends CreateRecord
{
    protected static string $resource = WebsitePageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['content'] = WebsitePageBuilder::package($data['page_sections'] ?? []);

        unset($data['page_sections']);

        return $data;
    }
}
