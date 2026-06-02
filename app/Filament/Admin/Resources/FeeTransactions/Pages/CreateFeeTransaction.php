<?php

namespace App\Filament\Admin\Resources\FeeTransactions\Pages;

use App\Filament\Admin\Resources\FeeTransactions\FeeTransactionResource;
use App\Models\FeeInvoice;
use Filament\Resources\Pages\CreateRecord;

class CreateFeeTransaction extends CreateRecord
{
    protected static string $resource = FeeTransactionResource::class;

    protected function afterCreate(): void
    {
        /** @var FeeInvoice|null $invoice */
        $invoice = $this->record->invoice;
        if ($invoice) {
            $invoice->refreshAmounts();
        }
    }
}

