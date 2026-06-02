<?php

namespace App\Filament\Student\Resources\MyFeeInvoiceResource\Pages;

use App\Filament\Student\Resources\MyFeeInvoiceResource\MyFeeInvoiceResource;
use App\Services\Payments\GatewayManager;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewMyFeeInvoice extends ViewRecord
{
    protected static string $resource = MyFeeInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('pay_now')
                ->label('Pay Now')
                ->icon('heroicon-o-credit-card')
                ->form([
                    \Filament\Forms\Components\TextInput::make('amount')->required()->numeric()->minValue(1),
                    \Filament\Forms\Components\Select::make('method')
                        ->required()
                        ->options(app(GatewayManager::class)->enabledGateways()),
                    \Filament\Forms\Components\TextInput::make('reference'),
                ])
                ->visible(fn (): bool => in_array($this->record->status, ['issued', 'partial', 'overdue'], true))
                ->action(function (array $data): void {
                    app(\App\Services\FeeManagement\FeeEngine::class)->recordPayment(
                        $this->record,
                        (float) $data['amount'],
                        (string) $data['method'],
                        ['reference' => $data['reference'] ?? null]
                    );

                    Notification::make()->title('Payment submitted')->success()->send();
                    $this->refreshFormData(['status', 'total_paid']);
                }),
        ];
    }
}

