<?php

namespace App\Filament\Admin\Resources\WebsitePages\Pages;

use App\Filament\Admin\Resources\WebsitePages\Support\WebsitePageBuilder;
use App\Filament\Admin\Resources\WebsitePages\WebsitePageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsitePage extends EditRecord
{
    protected static string $resource = WebsitePageResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $sections = WebsitePageBuilder::extract($data['content'] ?? null);
        $legacyContent = WebsitePageBuilder::stripMeta($data['content'] ?? null);

        if ($sections !== []) {
            $data['page_sections'] = $sections;
        } elseif ($legacyContent !== '') {
            $data['page_sections'] = [[
                'section_key' => 'main-content',
                'section_title' => 'Main Content',
                'section_style' => 'default',
                'content' => $legacyContent,
            ]];
        } else {
            $data['page_sections'] = [];
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['content'] = WebsitePageBuilder::package($data['page_sections'] ?? []);

        unset($data['page_sections']);

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
