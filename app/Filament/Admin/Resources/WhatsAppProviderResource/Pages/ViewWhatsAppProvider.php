<?php

namespace App\Filament\Admin\Resources\WhatsAppProviderResource\Pages;

use App\Filament\Admin\Resources\WhatsAppProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsAppProvider extends ViewRecord
{
    protected static string $resource = WhatsAppProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
