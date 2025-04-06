<?php

namespace App\Filament\Admin\Resources\MessageTemplateResource\Pages;

use App\Filament\Admin\Resources\MessageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMessageTemplate extends ViewRecord
{
    protected static string $resource = MessageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
