<!DOCTYPE html>
<html>
<head>
    <title>Print Out Pass</title>
    <style>
        /* Global print settings for the page */
        @page {
            margin: 0.5in;   /* Standard margins, adjust as needed */
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0; /* Light background for screen viewing */
            text-align: center;
        }
        /* Your ID card styling from before */
        .id-card-container {
            width: 3.375in;
            height: 2.125in;
            border: 1px solid #ccc;
            font-family: Arial, sans-serif;
            font-size: 10px;
            box-sizing: border-box;
            padding: 5px;
            margin: 5px;
            border-radius: 10px;
            float: left; /* Arrange multiple cards on a page */
            page-break-inside: avoid; /* Prevent breaking cards across pages */
            position: relative; /* For better positioning if needed */
        }

        .title {
            font-size: 4rem;
            font-weight: bold;
            margin-top: 3rem;
        }

        .name {
            font-size: 1rem;
            margin-top: -3.2rem;
        }

        /* Add any other specific print styles */
        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .id-card-container {
                box-shadow: none; /* Remove shadows for print */
                border: 1px solid #ccc; /* Ensure borders are visible */
            }
        }
    </style>
</head>
<body>
@foreach($records as $record)
    <div class="id-card-container">
        <h1 class="title">Out Pass</h1>
        <p class="name">
            {{$record->name}}
        </p>
    </div>
    <div class="id-card-container">
        <h1 class="title">Out Pass</h1>
        <p class="name">
            {{$record->name}}
        </p>
    </div>
@endforeach

<script>
    // Automatically trigger print dialog when the page loads
    window.onload = function () {
        window.print();
    };
</script>
</body>
</html>
