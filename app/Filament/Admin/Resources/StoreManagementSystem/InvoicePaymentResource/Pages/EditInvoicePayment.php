<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditInvoicePayment extends EditRecord
{
    protected static string $resource = InvoicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
