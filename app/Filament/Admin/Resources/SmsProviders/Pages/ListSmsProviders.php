<?php

namespace App\Filament\Admin\Resources\SmsProviders\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\SmsProviders\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSmsProviders extends ListRecords
{
    protected static string $resource = SmsProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
