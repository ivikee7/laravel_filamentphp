<?php

namespace App\Filament\Admin\Resources\PaymentWebhookEvents\Pages;

use App\Filament\Admin\Resources\PaymentWebhookEvents\PaymentWebhookEventResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Schemas\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class ViewPaymentWebhookEvent extends ViewRecord
{
    protected static string $resource = PaymentWebhookEventResource::class;

    public function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Webhook Event')
                ->schema([
                    TextEntry::make('driver'),
                    TextEntry::make('event_id'),
                    TextEntry::make('payment_reference'),
                    TextEntry::make('provider_payment_id'),
                    TextEntry::make('signature_valid')->badge(),
                    TextEntry::make('status')->badge(),
                    TextEntry::make('attempts'),
                    TextEntry::make('error_message')->placeholder('-')->columnSpanFull(),
                    TextEntry::make('processed_at')->dateTime()->placeholder('-'),
                    TextEntry::make('created_at')->dateTime(),
                    TextEntry::make('updated_at')->dateTime(),
                ])
                ->columns(2),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}

