<?php

namespace App\Filament\Admin\Resources\FeeInvoices\Pages;

use App\Filament\Admin\Resources\FeeInvoices\FeeInvoiceResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class PrintFeeInvoice extends Page
{
    use InteractsWithRecord;

    protected static string $resource = FeeInvoiceResource::class;

    protected string $view = 'filament.admin.resources.fee-invoices.pages.print-fee-invoice';

    protected static string $layout = 'layouts.print';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->record->load(['student.user', 'items.feeHead', 'transactions']);
    }
}

