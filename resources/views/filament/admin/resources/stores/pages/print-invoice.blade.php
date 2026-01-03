<div>
    <div class="grid-container">
        @for($i=0; $i<2;$i++)
            <div class="item">
                <div>
                    <h3>INVOICE</h3>
                </div>
                <table class="table">
                    <tr>
                        <td>
                            <div>
                                <p>Invoice #: {{ $this->invoice->id }}</p>
                                <h4>To:</h4>
                                <p>Name: {{ $this->invoice->user->name ?? '' }}</p>
{{--                                <p>--}}
{{--                                    Address: {{ $this->invoice->user->address ?? '' }}--}}
{{--                                    , {{ $this->invoice->user->city ?? '' }}--}}
{{--                                    , {{ $this->invoice->user->state ?? '' }}--}}
{{--                                    , {{ $this->invoice->user->pin_code ?? '' }}--}}
{{--                                </p>--}}
                                <p>Contact: {{ $this->invoice->user->primary_contact_number ?? '' }}</p>
                                <p>Class: {{ $this->invoice->class->name ?? '' }}</p>
                            </div>
                        </td>
                        <td>
                            <div>
                                <p>Date: {{ $this->invoice->created_at->format('M d, Y') }}</p>
                                <h4>From:</h4>
                                <p>{{ $record->name ?? '' }}</p>
                                <p>Address: {{ $record->address ?? '' }}, {{ $record->city ?? '' }}
                                    , {{ $record->state ?? '' }}
                                    , {{ $record->pin_code ?? '' }}</p>
                                <p>Contact: {{ $record->phone ?? '' }}, {{ $record->email ?? '' }}</p>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Description</th>
                        <th>Qty</th>
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
                        <td rowspan="5"></td>
                        <td colspan="2">Sub Total</td>
                        <td>{{ number_format($this->invoice->subtotal_amount ?? 00, 2) }}</td>
                    </tr>
                    <tr>
                        @if($this->invoice->discount_amount > 0)
                            <td colspan="2">Discount</td>
                            <td>{{ number_format($this->invoice->discount_amount ?? 00, 2) }}</td>
                        @endif
                    </tr>
                    <tr>
                        <td colspan="2">Total</td>
                        <td>{{ number_format($this->invoice->total_amount ?? 00, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Paid</td>
                        <td>{{ number_format($this->invoice->total_paid_amount ?? 00, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">Due</td>
                        <td>{{ number_format($this->invoice->total_due_amount ?? 00, 2) }}</td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        @endfor
    </div>
</div>

@push('styles')
    <style>
        * {
            margin: 0;
            padding: 0;
            text-decoration: none;
        }

        body {
            /*padding: 0.5rem;*/
            font-size: 0.7rem;
        }

        .grid-container {
            display: grid !important;
            grid-template-columns: 50% 1fr !important;
            gap: 10px !important;
        }

        .item {
            padding: 8px !important;
            border: 1px solid #ccc !important;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 0.1rem; /* Add padding inside cells */
            text-align: left; /* Align text to the left */
            vertical-align: top; /* Align content to the top of the cell */
        }

        th {
            background-color: #f2f2f2; /* Light grey background for headers */
            font-weight: bold; /* Bold text for headers */
        }

        /* Table Bordered Styling (using a class for reusability) */
        .table-bordered {
            border: 1px solid #dee2e6; /* Outer border for the entire table */
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6; /* Borders for individual cells */
        }

        /* Optional: Styling for hover effect on rows */
        .table-hover tbody tr:hover {
            background-color: #f5f5f5; /* Light grey background on hover */
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Function to handle the print process
        function doPrintAndReturn() {
            window.focus(); // Ensure the window is active
            window.print();
        }

        // Add event listener for when printing is finished or canceled using modern API
        if (window.matchMedia) {
            var mediaQueryList = window.matchMedia('print');
            mediaQueryList.addListener(function (mql) {
                if (!mql.matches) {
                    // This fires when the user closes the print dialog
                    window.history.back();
                }
            });
        }

        // Fallback for browsers that don't support matchMedia for print events
        window.onafterprint = function () {
            window.history.back();
        };

        // Trigger the process immediately when the page loads
        window.onload = function () {
            doPrintAndReturn();
        }
    </script>
@endpush
