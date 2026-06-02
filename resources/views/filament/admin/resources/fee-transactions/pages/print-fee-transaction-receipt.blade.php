@php
    $status = strtoupper((string) $this->record->status);
@endphp

<style>
    .receipt-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: 14px;
        font-family: Arial, Helvetica, sans-serif;
        color: #000;
        font-size: 12px;
    }

    .receipt {
        border: 1px solid #000;
        padding: 12px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        border-bottom: 1px solid #000;
        padding-bottom: 8px;
        margin-bottom: 10px;
    }

    .title {
        font-size: 22px;
        font-weight: 700;
        margin: 0;
    }

    .status {
        border: 1px solid #000;
        padding: 2px 8px;
        font-weight: 700;
        display: inline-block;
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px 16px;
    }

    .label {
        color: #222;
        font-size: 11px;
    }

    .value {
        font-size: 13px;
        font-weight: 600;
    }

    .amount-box {
        margin-top: 12px;
        border: 1px solid #000;
        padding: 10px;
        font-size: 16px;
        font-weight: 700;
        text-align: center;
    }

    .footer {
        margin-top: 10px;
        text-align: right;
        font-size: 11px;
    }

    @media print {
        @page {
            margin: 10mm;
        }
    }
</style>

<div class="receipt-wrap">
    <div class="receipt">
        <div class="header">
            <div>
                <h1 class="title">Payment Receipt</h1>
                <div>Receipt #: {{ $this->record->id }}</div>
            </div>

            <div style="text-align:right;">
                <div class="status">{{ $status }}</div>
                <div style="margin-top:6px;">{{ optional($this->record->payment_date)->format('d M Y h:i A') }}</div>
            </div>
        </div>

        <div class="grid">
            <div>
                <div class="label">Student Name</div>
                <div class="value">{{ $this->record->student?->user?->name ?? 'N/A' }}</div>
            </div>

            <div>
                <div class="label">Admission #</div>
                <div class="value">{{ $this->record->student?->admission_number ?? 'N/A' }}</div>
            </div>

            <div>
                <div class="label">Invoice #</div>
                <div class="value">{{ $this->record->invoice?->invoice_no ?? 'N/A' }}</div>
            </div>

            <div>
                <div class="label">Method</div>
                <div class="value">{{ strtoupper((string) $this->record->method) }}</div>
            </div>

            <div>
                <div class="label">Reference</div>
                <div class="value">{{ $this->record->reference ?: 'N/A' }}</div>
            </div>

            <div>
                <div class="label">Gateway</div>
                <div class="value">{{ strtoupper((string) ($this->record->gateway_driver ?: $this->record->method)) }}</div>
            </div>
        </div>

        <div class="amount-box">
            Amount Received: INR {{ number_format((float) $this->record->amount, 2) }}
        </div>

        <div class="footer">
            Generated at {{ now()->format('d M Y h:i A') }}
        </div>
    </div>
</div>

