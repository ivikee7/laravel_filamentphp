<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoicePayments extends ListRecords
{
    protected static string $resource = InvoicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
