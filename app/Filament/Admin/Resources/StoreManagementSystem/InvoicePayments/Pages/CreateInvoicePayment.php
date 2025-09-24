<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\InvoicePayments\InvoicePaymentResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoicePayment extends CreateRecord
{
    protected static string $resource = InvoicePaymentResource::class;
}
