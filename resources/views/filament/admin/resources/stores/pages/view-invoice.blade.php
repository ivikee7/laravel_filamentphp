<x-filament-panels::page>
    {{ $this->invoiceInfolist }}
    <div>
        @foreach($this->getInvoiceActions() as $action)
            {{
                $action->record($invoice)
            }}
        @endforeach
    </div>
    {{ $this->getPaymentsTable() }}
    {{ $this->table }}



</x-filament-panels::page>
