<?php

namespace App\Filament\Admin\Resources\Stores\Pages;

use App\Filament\Admin\Resources\Stores\StoreResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;

class PrintInvoice extends Page
{
    use InteractsWithRecord;

    protected static string $resource = StoreResource::class;

    protected string $view = 'filament.admin.resources.stores.pages.print-invoice';

    public $invoice; // Make sure this property is public

    public function mount(int|string $record, int|string $invoiceId = null): void
    {
        $this->record = $this->resolveRecord($record);

        if ($invoiceId) {
            // Assuming 'storeInvoices' is the correct relationship name
            $this->invoice = $this->record->storeInvoices()->findOrFail($invoiceId);
        }
    }

    public function getLayout(): string
    {
        return 'layouts.print'; // Points to resources/views/layouts/print.blade.php
    }

    public function getTitle(): string | Htmlable
    {
        return 'Print Invoice';
    }

    public function getHeading(): string
    {
        // Add a check in case $invoice is null for some reason
        if (!$this->invoice) {
            return 'Invoice Details';
        }
        return 'Invoice #' . $this->invoice->id ?? 'N/A';
    }
}
