<?php

namespace App\Filament\Admin\Resources\SmsProviders\Pages;

use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use App\Filament\Admin\Resources\SmsProviders\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSmsProvider extends EditRecord
{
    protected static string $resource = SmsProviderResource::class;

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
