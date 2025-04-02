<?php

namespace App\Filament\Admin\Resources\SmsProviderResource\Pages;

use App\Filament\Admin\Resources\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSmsProvider extends ViewRecord
{
    protected static string $resource = SmsProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
