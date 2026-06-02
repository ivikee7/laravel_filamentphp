<?php

namespace App\Filament\Admin\Resources\FeeInvoices\Pages;

use App\Filament\Admin\Resources\FeeInvoices\FeeInvoiceResource;
use App\Models\FeeInvoice;
use App\Services\FeeManagement\FeeEngine;
use App\Services\Payments\GatewayManager;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewFeeInvoice extends ViewRecord
{
    protected static string $resource = FeeInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_invoice')
                ->label('Print Invoice')
                ->icon('heroicon-o-printer')
                ->url(fn (): string => FeeInvoiceResource::getUrl('print', ['record' => $this->record]))
                ->openUrlInNewTab(),

            Action::make('record_payment')
                ->label('Record Payment')
                ->icon('heroicon-o-banknotes')
                ->form([
                    TextInput::make('amount')->required()->numeric()->minValue(1),
                    Select::make('method')
                        ->required()
                        ->options(app(GatewayManager::class)->enabledGateways()),
                    TextInput::make('reference'),
                ])
                ->action(function (array $data): void {
                    /** @var FeeInvoice $invoice */
                    $invoice = $this->record;

                    app(FeeEngine::class)->recordPayment(
                        $invoice,
                        (float) $data['amount'],
                        (string) $data['method'],
                        ['reference' => $data['reference'] ?? null]
                    );

                    Notification::make()->title('Payment recorded')->success()->send();
                    $this->refreshFormData(['status', 'total_paid', 'total_due']);
                }),
        ];
    }
}

