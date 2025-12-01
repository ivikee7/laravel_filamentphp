<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\StoreInvoiceTransactionResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStoreInvoiceTransaction extends ViewRecord
{
    protected static string $resource = StoreInvoiceTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
