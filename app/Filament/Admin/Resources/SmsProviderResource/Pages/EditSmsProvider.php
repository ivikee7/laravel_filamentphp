<?php

namespace App\Filament\Admin\Resources\SmsProviderResource\Pages;

use App\Filament\Admin\Resources\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmsProvider extends EditRecord
{
    protected static string $resource = SmsProviderResource::class;

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
