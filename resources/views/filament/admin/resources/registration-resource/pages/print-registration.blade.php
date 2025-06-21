<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Print - {{ $this->record->id }}</title>
    <style>
        /* Add your custom print styles here */
        body {
            font-family: Arial, sans-serif;
            margin: 20mm; /* Example margin for print */
        }
        .print-header {
            text-align: center;
            margin-bottom: 20mm;
        }
        .section {
            margin-bottom: 10mm;
        }
        .section-title {
            font-weight: bold;
            margin-bottom: 5mm;
            border-bottom: 1px solid #ccc;
            padding-bottom: 3mm;
        }
        .data-row {
            margin-bottom: 2mm;
        }
        .data-label {
            font-weight: bold;
            display: inline-block;
            width: 30%; /* Adjust as needed */
        }
        .data-value {
            display: inline-block;
            width: 65%; /* Adjust as needed */
        }

        /* Styles specifically for printing (media query) */
        @media print {
            body {
                margin: 0;
            }
            .no-print {
                display: none !important;
            }
            /* Further print-specific adjustments */
        }
    </style>
</head>
<body>
{{-- This is the single root element for Livewire within the body --}}
<div>
    <div class="print-header">
        <h1>Registration Details</h1>
        <p>Date: {{ now()->format('Y-m-d H:i') }}</p>
    </div>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="data-row">
            <span class="data-label">Name:</span>
            <span class="data-value">{{ $this->record->name }}</span>
        </div>
        <div class="data-row">
            <span class="data-label">Email:</span>
            <span class="data-value">{{ $this->record->email }}</span>
        </div>
        <div class="data-row">
            <span class="data-label">Phone:</span>
            <span class="data-value">{{ $this->record->phone_number }}</span>
        </div>
        {{-- Add more fields from your Registration model --}}
    </div>

    <div class="section">
        <div class="section-title">Registration Details</div>
        <div class="data-row">
            <span class="data-label">Registration Date:</span>
            <span class="data-value">{{ $this->record->created_at->format('Y-m-d') }}</span>
        </div>
        {{-- Add other registration-specific details --}}
    </div>
</div>
{{-- End of the single root element --}}

{{-- The script tag is outside the Livewire root, which is generally fine for print pages --}}
<script>
    window.onload = function() {
        window.print();
        // Optional: Close the window after printing
        // window.onafterprint = function() {
        //     window.close();
        // };
    };
</script>
</body>
</html>
