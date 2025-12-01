<?php

namespace App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\Pages;

use App\Filament\Admin\Resources\Stores\Resources\StoreInvoices\Resources\StoreInvoiceTransactions\StoreInvoiceTransactionResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStoreInvoiceTransaction extends EditRecord
{
    protected static string $resource = StoreInvoiceTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
