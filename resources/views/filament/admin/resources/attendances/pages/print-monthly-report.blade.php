<div class="print-container">
    <style>
        /* Styles needed for the report itself */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
        }

        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
            font-size: 0.8em;
        }

        @media print {
            .print-button {
                display: none;
            }
        }
    </style>

    <button onclick="window.print()" class="print-button">Print Report</button>
    <h2>Monthly Attendance Report ({{ $start_date ?? '' }} to {{ $end_date ?? '' }})</h2>
    <table>
        <thead>
        <tr>
            @foreach($printColumns as $col)
                <th>{{ $col }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
            @foreach($printRecords as $record)
                <tr>
                    @foreach($printColumns as $index => $col)
                        @php
                            // Try to map column to a value in record. If column_keys exist, use keys; otherwise try common keys
                            $value = null;
                            if (!empty($printColumnKeys) && isset($printColumnKeys[$index]) && $printColumnKeys[$index]) {
                                $value = data_get($record, $printColumnKeys[$index]);
                            } else {
                                // common fallback keys
                                if (array_key_exists('id', $record)) {
                                    $value = data_get($record, 'id');
                                }
                                if ($value === null && array_key_exists('name', $record)) {
                                    $value = data_get($record, 'name');
                                }
                                if ($value === null && array_key_exists('roles', $record)) {
                                    $value = data_get($record, 'roles');
                                }
                                // If column represents a date (format d-m-Y), try to fetch that date key
                                if ($value === null) {
                                    foreach ($record as $k => $v) {
                                        if ($k === $col || $k === str_replace('-', '', $col) || $k === str_replace('-', '', str_replace('/', '', $col))) {
                                            $value = $v;
                                            break;
                                        }
                                    }
                                }
                            }
                        @endphp
                        @php
                            $valueStr = '';
                            if (is_array($value)) {
                                $valueStr = implode(', ', $value);
                            } else {
                                $valueStr = (string) ($value ?? '');
                            }
                        @endphp
                        <td>{!! nl2br(e($valueStr)) !!}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        setTimeout(function () {
            window.print();
        }, 500);
    </script>
</div>
