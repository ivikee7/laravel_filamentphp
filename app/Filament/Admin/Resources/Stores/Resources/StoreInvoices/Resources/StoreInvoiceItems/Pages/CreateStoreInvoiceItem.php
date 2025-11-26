<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceItems\StoreInvoiceItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreInvoiceItem extends CreateRecord
{
    protected static string $resource = StoreInvoiceItemResource::class;
}
