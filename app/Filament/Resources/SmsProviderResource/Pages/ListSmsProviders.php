<?php

namespace App\Filament\Resources\SmsProviderResource\Pages;

use App\Filament\Resources\SmsProviderResource;
use App\Models\SmsProvider;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmsProviders extends ListRecords
{
    protected static string $resource = SmsProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('SendBulkSms')
                ->url(fn(): string => SmsProviderResource::getUrl('send-bulk-sms')),
        ];
    }
}
