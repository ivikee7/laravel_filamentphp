<?php

namespace App\Filament\Admin\Resources\WhatsAppProviders\Pages;

use Filament\Actions\EditAction;
use App\Filament\Admin\Resources\WhatsAppProviders\WhatsAppProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewWhatsAppProvider extends ViewRecord
{
    protected static string $resource = WhatsAppProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
