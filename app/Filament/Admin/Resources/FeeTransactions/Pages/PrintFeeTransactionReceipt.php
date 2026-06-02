<?php

namespace App\Filament\Admin\Resources\FeeTransactions\Pages;

use App\Filament\Admin\Resources\FeeTransactions\FeeTransactionResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class PrintFeeTransactionReceipt extends Page
{
    use InteractsWithRecord;

    protected static string $resource = FeeTransactionResource::class;

    protected string $view = 'filament.admin.resources.fee-transactions.pages.print-fee-transaction-receipt';

    protected static string $layout = 'layouts.print';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->load(['student.user', 'invoice']);
    }
}

