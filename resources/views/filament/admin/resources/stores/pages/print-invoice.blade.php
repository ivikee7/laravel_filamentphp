<div>
    @push('styles')
    @endpush

    <div>
        <div>

            <div>
                <h1>INVOICE</h1>
                <div>
                    <p>Invoice #: {{ $this->invoice->id }}</p>
                    <p>
                        Date: {{ $this->invoice->created_at->format('M d, Y') }}</p>
                </div>
            </div>

            <div>
                <div>
                    <h2>To:</h2>
                    <p>{{ $this->invoice->user->name ?? '' }}</p>
                    <p>{{ $this->invoice->user->address ?? '' }}</p>
                    <p>{{ $this->invoice->user->email ?? '' }}</p>
                </div>

                <div>
                    <h2>From:</h2>
                    <p>{{ $record->name ?? '' }}</p>
                    <p>{{ $record->address ?? '' }}</p>
                    <p>Phone: {{ $record->phone ?? '' }} |
                        Email: {{ $record->email ?? '' }}</p>
                </div>
            </div>

            <table>
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
            </table>

            <div>
                <div>
                    <div>
                        <span>Total:</span>
                        <span
                        >{{ number_format($this->invoice->total_amount ?? 00, 2) }}</span>
                    </div>
                    <div>
                        <span>Paid:</span>
                        <span
                        >{{ number_format($this->invoice->total_paid_amount ?? 00, 2) }}</span>
                    </div>
                    <div>
                        <span>Due:</span>
                        <span
                        >{{ number_format($this->invoice->total_due_amount ?? 00, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
