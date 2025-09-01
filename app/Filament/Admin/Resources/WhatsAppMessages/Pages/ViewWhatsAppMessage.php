<?php

namespace App\Filament\Admin\Resources\WhatsAppMessages\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\WhatsAppMessages\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsAppMessage extends ViewRecord
{
    protected static string $resource = WhatsAppMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
