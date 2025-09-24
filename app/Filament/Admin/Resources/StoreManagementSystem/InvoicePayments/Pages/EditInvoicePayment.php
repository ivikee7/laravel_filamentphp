<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages;

use Filament\Actions\DeleteAction;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\InvoicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoicePayment extends EditRecord
{
    protected static string $resource = InvoicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
