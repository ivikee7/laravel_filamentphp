<?php

namespace App\Filament\Admin\Resources\WebsiteMarquees\Pages;

use App\Filament\Admin\Resources\WebsiteMarquees\WebsiteMarqueeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWebsiteMarquee extends EditRecord
{
    protected static string $resource = WebsiteMarqueeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
