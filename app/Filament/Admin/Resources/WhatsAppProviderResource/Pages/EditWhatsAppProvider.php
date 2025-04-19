<?php

namespace App\Filament\Admin\Resources\WhatsAppProviderResource\Pages;

use App\Filament\Admin\Resources\WhatsAppProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppProvider extends EditRecord
{
    protected static string $resource = WhatsAppProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
