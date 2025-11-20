<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreTransactions\StoreTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreTransaction extends CreateRecord
{
    protected static string $resource = StoreTransactionResource::class;
}
