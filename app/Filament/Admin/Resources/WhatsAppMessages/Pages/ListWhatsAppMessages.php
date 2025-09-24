<?php

namespace App\Filament\Admin\Resources\WhatsAppMessages\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\WhatsAppMessages\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWhatsAppMessages extends ListRecords
{
    protected static string $resource = WhatsAppMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
