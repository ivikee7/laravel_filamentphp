<?php

namespace App\Filament\Admin\Resources\StoreManagementSystem\Invoices\Pages;

use App\Filament\Admin\Resources\StoreManagementSystem\Invoices\InvoiceResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
