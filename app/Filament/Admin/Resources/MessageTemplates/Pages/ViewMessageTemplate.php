<?php

namespace App\Filament\Admin\Resources\MessageTemplates\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\MessageTemplates\MessageTemplateResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewMessageTemplate extends ViewRecord
{
    protected static string $resource = MessageTemplateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
