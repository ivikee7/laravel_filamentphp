<?php

namespace App\Filament\Admin\Resources\WhatsAppProviders\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\WhatsAppProviders\WhatsAppProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppProvider extends EditRecord
{
    protected static string $resource = WhatsAppProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
