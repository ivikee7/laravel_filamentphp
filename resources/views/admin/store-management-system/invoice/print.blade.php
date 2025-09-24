<h1 > Invoice #{{ $invoice->invoice_number }}</h1>

<p ><strong > User:</strong > {{ $invoice->user->name }}</p>
<p><strong>Store:</strong> {{ $invoice->store->name ?? 'N/A' }}</p>

<table>
    <thead>
    <tr>
        <th>Item</th>
        <th>Qty</th>
        <th>Price</th>
        <th>Subtotal</th>
    </tr>
    </thead>
    <tbody>
    @foreach($invoice->items as $item)
        <tr>
            <td>{{ $item->product->name }}</td>
            <td>{{ $item->quantity }}</td>
            <td>₹{{ $item->product->price }}</td>
            <td>₹{{ $item->product->price * $item->quantity }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p><strong>Total:</strong> ₹{{ $invoice->total }}</p>

<h3>Payments</h3>
<ul>
    @foreach($invoice->payments as $payment)
        <li>{{ $payment->payment_date }} — ₹{{ $payment->amount }} via {{ ucfirst($payment->payment_method) }}</li>
    @endforeach
</ul>
