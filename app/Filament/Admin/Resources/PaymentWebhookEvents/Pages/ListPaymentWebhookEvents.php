<?php

namespace App\Filament\Admin\Resources\PaymentWebhookEvents\Pages;

use App\Filament\Admin\Resources\PaymentWebhookEvents\PaymentWebhookEventResource;
use Filament\Resources\Pages\ListRecords;

class ListPaymentWebhookEvents extends ListRecords
{
    protected static string $resource = PaymentWebhookEventResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

