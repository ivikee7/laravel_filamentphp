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
    <h2>Monthly Attendance Report ({{  $print_data[0]['start_data'] . ' to ' . $print_data[0]['end_date']  }})</h2>
    <table>
        <thead>
        <tr>
            @foreach($print_data[0]['columns'] as $label => $value)
                <th>{{ $label }}</th>
            @endforeach
        </tr>
        </thead>
        <tbody>
                @foreach($print_data[0]['records'] as $record)
                    <tr>
                        <td>{{ $record->id ?? '' }}</td>
                        <td>{{ $record->name ?? '' }}</td>
                        <td>{{ $record->roles ?? '' }}</td>

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
