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
    <h2>Monthly Attendance Report</h2>
    <table>
        <thead>
        <tr>
            @dd($print_data);
{{--            @foreach($this->print_columns as $label)--}}
{{--                <th>{{ $label }}</th>--}}
{{--            @endforeach--}}
        </tr>
        </thead>
        <tbody>
{{--        @foreach($this->printData as $rowData)--}}
{{--            <tr>--}}
{{--                @foreach($this->printColumns as $colName)--}}
{{--                    --}}{{-- Output the pre-processed data --}}
{{--                    <td>{{ $rowData[$colName] ?? '' }}</td>--}}
{{--                @endforeach--}}
{{--            </tr>--}}
{{--        @endforeach--}}
        </tbody>
    </table>

    <script>
        setTimeout(function () {
            window.print();
        }, 500);
    </script>
</div>
