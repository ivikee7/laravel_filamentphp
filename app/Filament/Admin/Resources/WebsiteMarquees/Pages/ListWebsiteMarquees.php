<?php

namespace App\Filament\Admin\Resources\WebsiteMarquees\Pages;

use App\Filament\Admin\Resources\WebsiteMarquees\WebsiteMarqueeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteMarquees extends ListRecords
{
    protected static string $resource = WebsiteMarqueeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
