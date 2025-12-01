<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\StoreInvoiceTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreInvoiceTransaction extends CreateRecord
{
    protected static string $resource = StoreInvoiceTransactionResource::class;
}
