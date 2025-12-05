@push('styles')
    <style>
        .grid-container {
            display: grid !important; /* Establishes a grid container */
            /* Defines two columns: one fixed width (200px), one flexible (1fr) */
            grid-template-columns: 50% 1fr !important;
            gap: 10px !important; /* Adds space between the grid items */
        }

        .item {
            padding: 15px !important;
            border: 1px solid #ccc !important;
        }

        /*.table {*/
        /*    border: 1px solid black !important;*/
        /*    display: block !important;*/
        /*    width: 100% !important;*/
        /*}*/
    </style>
@endpush

<div>
    <div class="grid-container">
        @for($i=0; $i<2;$i++)
            <div class="item">
                <div>
                    <h1>INVOICE</h1>
                </div>
                <table>
                    <tr>
                        <td>
                            <div>
                                <p>Invoice #: {{ $this->invoice->id }}</p>
                                <h2>To:</h2>
                                <p>Address: {{ $this->invoice->user->name ?? '' }}
                                    , {{ $this->invoice->user->address ?? '' }}</p>
                                <p>Contact: {{ $this->invoice->user->primary_contact_number ?? '' }}
                                    , {{ $this->invoice->user->secondary_contact_number ?? '' }}</p>
                                <p>
                                    Class: {{ $this->invoice->user->student->classAssignment->class->name ?? '' }}</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p>Date: {{ $this->invoice->created_at->format('M d, Y') }}</p>
                                <h2>From:</h2>
                                <p>{{ $record->name ?? '' }}</p>
                                <p>Address: {{ $record->address ?? '' }}, {{ $record->city ?? '' }}
                                    , {{ $record->state ?? '' }}
                                    , {{ $record->pin_code ?? '' }}</p>
                                <p>Contact: {{ $record->phone ?? '' }}, {{ $record->email ?? '' }}</p>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="table">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($this->invoice->storeInvoiceItems && count($this->invoice->storeInvoiceItems) > 0)
                        @foreach($this->invoice->storeInvoiceItems as $item)
                            <tr>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price, 2) }}</td>
                                <td>{{ number_format(($item->price * $item->quantity), 2) }}</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td>Class 1 Book and Stationary 1</td>
                            <td>1</td>
                            <td>8,000.00</td>
                            <td>8,000.00</td>
                        </tr>
                    @endif
                    </tbody>
                    <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Total</td>
                        <td>{{ number_format($this->invoice->total_amount ?? 00, 2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Paid</td>
                        <td>{{ number_format($this->invoice->total_paid_amount ?? 00, 2) }}</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Due</td>
                        <td>{{ number_format($this->invoice->total_due_amount ?? 00, 2) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @endfor
    </div>
</div>
