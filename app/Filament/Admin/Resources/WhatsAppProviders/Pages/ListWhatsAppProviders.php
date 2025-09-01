<?php

namespace App\Filament\Admin\Resources\WhatsAppProviders\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\WhatsAppProviders\WhatsAppProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWhatsAppProviders extends ListRecords
{
    protected static string $resource = WhatsAppProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
