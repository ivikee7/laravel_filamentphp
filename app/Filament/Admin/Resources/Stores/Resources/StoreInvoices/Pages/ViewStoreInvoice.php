<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\StoreInvoiceResource;
use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;

class ViewStoreInvoice extends ViewRecord
{
    protected static string $resource = StoreInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
