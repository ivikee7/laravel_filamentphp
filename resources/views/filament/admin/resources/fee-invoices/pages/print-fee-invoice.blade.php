@php
    $balance = max(0, (float) $this->record->total_due - (float) $this->record->total_paid);
    $status = strtoupper((string) $this->record->status);
@endphp

<style>
    .sheet {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6mm;
        font-family: Arial, Helvetica, sans-serif;
        color: #000;
        font-size: 12px;
    }

    .invoice-copy {
        border: 1px solid #000;
        padding: 10px;
        break-inside: avoid;
    }

    .top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 10px;
    }

    .title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .muted {
        color: #222;
        margin-top: 2px;
    }

    .status {
        font-weight: 700;
        border: 1px solid #000;
        padding: 3px 8px;
        display: inline-block;
        margin-bottom: 6px;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 8px;
        margin-bottom: 10px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 10px;
    }

    th,
    td {
        border: 1px solid #000;
        padding: 6px;
    }

    th {
        text-align: left;
        font-weight: 700;
    }

    .right {
        text-align: right;
    }

    .totals {
        display: inline-block;
        margin-left: auto;
    }

    .totals-row {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #000;
        padding: 3px 0;
    }

    .balance {
        font-size: 14px;
        font-weight: 700;
    }

    .footer {
        margin-top: 8px;
        font-size: 11px;
        text-align: right;
    }

    @media print {
        @page {
            margin: 10mm;
        }

        .sheet {
            gap: 5mm;
        }
    }
</style>

<div class="sheet">
    @foreach([1, 2] as $copy)
        <div class="invoice-copy">
            <div class="top">
                <div>
                    <h1 class="title">Fee Invoice</h1>
                    <div class="muted">Invoice #: {{ $this->record->invoice_no }}</div>
                    <div class="muted">Copy {{ $copy }}</div>
                </div>

                <div class="right">
                    <div class="status">{{ $status }}</div>
                    <div class="muted">Period: {{ optional($this->record->period_start)->format('d M Y') }} - {{ optional($this->record->period_end)->format('d M Y') }}</div>
                    <div class="muted">Due Date: {{ optional($this->record->due_date)->format('d M Y') }}</div>
                </div>
            </div>

            <div class="grid">
                <div><strong>Name:</strong><br>{{ $this->record->student?->user?->name ?? 'N/A' }}</div>
                <div><strong>Admission #:</strong><br>{{ $this->record->student?->admission_number ?? 'N/A' }}</div>
                <div><strong>Email:</strong><br>{{ $this->record->student?->user?->email ?? 'N/A' }}</div>
            </div>

            <table>
                <thead>
                <tr>
                    <th>Item</th>
                    <th class="right">Qty</th>
                    <th class="right">Unit</th>
                    <th class="right">Discount</th>
                    <th class="right">Total</th>
                </tr>
                </thead>
                <tbody>
                @foreach($this->record->items as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td class="right">{{ $item->quantity }}</td>
                        <td class="right">{{ number_format((float) $item->unit_amount, 2) }}</td>
                        <td class="right">{{ number_format((float) $item->discount_amount, 2) }}</td>
                        <td class="right">{{ number_format((float) $item->line_total, 2) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>

            <div class="totals">
                <div class="totals-row"><span>Sub Total</span><span>{{ number_format((float) $this->record->sub_total, 2) }}</span></div>
                <div class="totals-row"><span>Discount</span><span>{{ number_format((float) $this->record->discount_total, 2) }}</span></div>
                <div class="totals-row"><span>Late Fee</span><span>{{ number_format((float) $this->record->late_fee, 2) }}</span></div>
                <div class="totals-row"><span>Total Due</span><span>{{ number_format((float) $this->record->total_due, 2) }}</span></div>
                <div class="totals-row"><span>Total Paid</span><span>{{ number_format((float) $this->record->total_paid, 2) }}</span></div>
                <div class="totals-row balance"><span>Balance</span><span>{{ number_format($balance, 2) }}</span></div>
            </div>

            <div class="footer">Generated at {{ now()->format('d M Y h:i A') }}</div>
        </div>
    @endforeach
</div>

