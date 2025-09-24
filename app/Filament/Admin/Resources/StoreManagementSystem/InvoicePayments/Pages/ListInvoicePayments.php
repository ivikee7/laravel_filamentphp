<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages;

use Filament\Actions\CreateAction;
use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\InvoicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListInvoicePayments extends ListRecords
{
    protected static string $resource = InvoicePaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
