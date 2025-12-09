<x-filament-panels::page>
    {{ $this->invoiceInfolist }}
    {{--    <div>--}}
    {{--        @foreach($this->getInvoiceActions() as $action)--}}
    {{--            {{--}}
    {{--                $action->record($invoice)--}}
    {{--            }}--}}
    {{--        @endforeach--}}
    {{--    </div>--}}
    {{ $this->table }}

{{--    {{ $this->itemsTable }}--}}

</x-filament-panels::page>
