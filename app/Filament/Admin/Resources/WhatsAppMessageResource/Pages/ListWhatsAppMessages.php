<?php

namespace App\Filament\Admin\Resources\WhatsAppMessageResource\Pages;

use App\Filament\Admin\Resources\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWhatsAppMessages extends ListRecords
{
    protected static string $resource = WhatsAppMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
