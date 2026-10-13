<div class="print-container">
    <style>
        @page {
            size: A4 landscape;
            margin: 8mm;
        }

        body {
            font-family: Arial, sans-serif;
            color: #000;
        }

        .print-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        h2 {
            font-size: 1.1rem;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }

        th, td {
            border: 1px solid #333;
            padding: 4px 6px;
            text-align: center;
            font-size: 0.75rem;
            white-space: pre-line;
        }

        th {
            background-color: #f2f2f2 !important;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #fafafa;
        }

        @media print {
            .print-button {
                display: none !important;
            }
        }
    </style>

    <div class="print-header">
        <h2>Monthly Attendance Report ({{ $start_date ?? '' }} to {{ $end_date ?? '' }})</h2>
        <button onclick="window.print()" class="print-button" style="padding: 6px 12px; cursor: pointer;">Print Report</button>
    </div>

    <table>
        <thead>
        <tr>
            @foreach($printColumns as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
        @forelse($printRecords as $record)
            <tr>
                @foreach($printColumnKeys as $key)
                    @php
                        $value = data_get($record, $key, '-');
                        if (is_array($value)) {
                            $value = implode(', ', $value);
                        }
                    @endphp
                    <td>{!! nl2br(e((string) $value)) !!}</td>
                @endforeach
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($printColumns) }}" style="text-align: center; padding: 20px;">No records available for printing.</td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <script>
        window.addEventListener('load', function () {
            setTimeout(function () {
                window.print();
            }, 400);
        });
    </script>
</div>
