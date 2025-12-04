<div>

    {{-- Add custom CSS styles for the invoice layout --}}
    @push('styles')
        <style>
            /* New wrapper for side-by-side layout using custom CSS flexbox */
            .invoices-wrapper {
                display: flex;
                justify-content: space-between; /* Puts space between the two invoice containers */
                gap: 20px; /* Space between the two invoices */
            }

            /* General container styles */
            .invoice-container {
                padding: 10px;
                width: 48%; /* Ensures two fit side-by-side */
                background-color: #ffffff;
                border: 1px solid #eee;
                font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
                color: #555;
                font-size: 13px;
            }

            /* Flexbox utility replacements (used for header/totals) */
            .flex-between {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 20px;
            }

            .flex-end {
                display: flex;
                justify-content: flex-end;
            }

            .text-right {
                text-align: right;
            }

            /* Typography */
            .text-3xl {
                font-size: 20px;
                font-weight: bold;
            }

            .font-bold {
                font-weight: bold;
            }

            .text-lg {
                font-size: 16px;
            }

            .font-semibold {
                font-weight: 600;
            }

            .mb-4 {
                margin-bottom: 10px;
            }

            .mb-20 {
                margin-bottom: 20px;
            }

            /* Table styles */
            .invoice-table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }

            .invoice-table th, .invoice-table td {
                padding: 8px 10px;
                text-align: left;
                border-bottom: 1px solid #ddd;
            }

            .invoice-table th {
                text-transform: uppercase;
                font-size: 11px;
                font-weight: 500;
                color: #666;
            }

            .invoice-table td.text-right {
                text-align: right;
            }

            /* Totals section */
            .border-t-total {
                border-top: 1px solid #eee;
                padding-top: 10px;
            }

            /* --- Print Specific Styles --- */
            @media print {
                body {
                    background-color: white !important;
                    color: black !important;
                    -webkit-print-color-adjust: exact;
                }

                .invoices-wrapper {
                    display: flex;
                    width: 100%;
                }

                .invoice-container {
                    box-shadow: none !important;
                    margin: 0 !important;
                    border: 1px solid #eee !important;
                    padding: 0 !important;
                    width: 49% !important;
                }
            }
        </style>
    @endpush

    {{-- Wrapper for the two side-by-side invoices --}}
    <div class="invoices-wrapper">

        <div class="invoice-container">

            {{-- Section 1: Header/IDs (INVOICE title on Left, Date/ID on Right) --}}
            <div class="flex-between">
                <h1 class="text-3xl font-bold">INVOICE</h1>
                <div class="text-right">
                    <p align="left" style="display: inline-block">Invoice #: {{ $this->invoice->id }}</p>
                    <p align="right" style="display: inline-block">
                        Date: {{ $this->invoice->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            {{-- Section 2: To and From details (Both aligned Left, Stacked Vertically) --}}
            <div>
                {{-- To (Client Details) --}}
                <div align="left" class="mb-4" style="display: inline-block">
                    <h2 class="font-semibold text-lg">To:</h2>
                    <p>{{ $this->invoice->user->name ?? 'PENELOPE FRANKLIN' }}</p>
                    <p>{{ $this->invoice->user->address ?? 'Non non iure qui culpa quas deleniti accusamus' }}</p>
                    <p>{{ $this->invoice->user->email ?? 'pabyfy@mailinator.com' }}</p>
                </div>

                {{-- From (Your/Store Details) --}}
                <div align="right" style="display: inline-block">
                    <h2 class="font-semibold text-lg">From:</h2>
                    <p>{{ $record->name ?? 'India Book Centre' }}</p>
                    <p>{{ $record->address ?? 'Voluptatem autem voluptates sapiente quis neque' }}</p>
                    <p>Phone: {{ $record->phone ?? '5555555555' }} |
                        Email: {{ $record->email ?? 'kityj@mailinator.com' }}</p>
                </div>
            </div>

            {{-- Section 3: Invoice Items Table --}}
            <table class="invoice-table">
                <thead>
                <tr>
                    <th>Description</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Total</th>
                </tr>
                </thead>
                <tbody>
                @if($this->invoice->storeInvoiceItems && count($this->invoice->storeInvoiceItems) > 0)
                    @foreach($this->invoice->storeInvoiceItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-right">{{ $item->quantity }}</td>
                            <td class="text-right">{{ number_format($item->price, 2) }}</td>
                            <td class="text-right">{{ number_format(($item->price * $item->quantity), 2) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>Class 1 Book and Stationary 1</td>
                        <td class="text-right">1</td>
                        <td class="text-right">8,000.00</td>
                        <td class="text-right">8,000.00</td>
                    </tr>
                @endif
                </tbody>
            </table>

            {{-- Section 4: Totals --}}
            <div class="flex-end">
                <div class="w-half">
                    <div class="flex-between border-t-total">
                        <span class="font-bold">Total Amount Due:</span>
                        <span
                            class="font-bold">{{ number_format($this->invoice->total_due_amount ?? 8000.00, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
