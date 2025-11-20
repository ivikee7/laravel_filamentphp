<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateStoreInvoice extends CreateRecord
{
    protected static string $resource = StoreInvoiceResource::class;
}
