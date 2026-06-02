<?php

namespace App\Filament\Admin\Resources\FeeTransactions\Pages;

use App\Filament\Admin\Resources\FeeTransactions\FeeTransactionResource;
use App\Models\FeeInvoice;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditFeeTransaction extends EditRecord
{
    protected static string $resource = FeeTransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /** @var FeeInvoice|null $invoice */
        $invoice = $this->record->invoice;
        if ($invoice) {
            $invoice->refreshAmounts();
        }
    }
}

