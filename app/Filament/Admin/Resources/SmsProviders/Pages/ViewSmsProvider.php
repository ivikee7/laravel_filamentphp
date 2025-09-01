<?php

namespace App\Filament\Admin\Resources\SmsProviders\Pages;

use Filament\Actions\EditAction;
use Filament\Actions\Action;
use App\Filament\Admin\Resources\SmsProviders\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSmsProvider extends ViewRecord
{
    protected static string $resource = SmsProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            Action::make('Send SMS')
                ->url(fn(): string => SmsProviderResource::getUrl('sendSms', [$this->record->id])),
        ];
    }
}
