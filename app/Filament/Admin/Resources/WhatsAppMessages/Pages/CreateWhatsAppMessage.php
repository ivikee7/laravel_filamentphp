<?php

namespace App\Filament\Admin\Resources\WhatsAppMessages\Pages;

use App\Filament\Admin\Resources\WhatsAppMessages\WhatsAppMessageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWhatsAppMessage extends CreateRecord
{
    protected static string $resource = WhatsAppMessageResource::class;
}
