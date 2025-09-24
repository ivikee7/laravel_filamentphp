<?php

namespace App\Filament\Admin\Resources\SmsProviders\Pages;

use App\Filament\Admin\Resources\SmsProviders\SmsProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSmsProvider extends CreateRecord
{
    protected static string $resource = SmsProviderResource::class;
}
