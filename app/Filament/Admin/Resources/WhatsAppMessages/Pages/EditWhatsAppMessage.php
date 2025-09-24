<?php

namespace App\Filament\Admin\Resources\WhatsAppMessages\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\WhatsAppMessages\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditWhatsAppMessage extends EditRecord
{
    protected static string $resource = WhatsAppMessageResource::class;

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
