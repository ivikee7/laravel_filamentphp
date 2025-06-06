<?php

namespace App\Filament\Admin\Resources\WhatsAppMessageResource\Pages;

use App\Filament\Admin\Resources\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppMessage extends EditRecord
{
    protected static string $resource = WhatsAppMessageResource::class;

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
