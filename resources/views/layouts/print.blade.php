<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- If you need Tailwind or specific styles, link them here -->
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        text-decoration: none;
    }
</style>
<body>
<!-- The content of your print-monthly-report view will be injected here -->
{{ $slot }}

{{-- Script to automatically trigger print and return to the previous page --}}
<script>
    // Function to handle the print process
    function doPrintAndReturn() {
        window.focus(); // Ensure the window is active
        window.print();
    }

    // Add event listener for when printing is finished or canceled using modern API
    if (window.matchMedia) {
        var mediaQueryList = window.matchMedia('print');
        mediaQueryList.addListener(function (mql) {
            if (!mql.matches) {
                // This fires when the user closes the print dialog
                window.history.back();
            }
        });
    }

    // Fallback for browsers that don't support matchMedia for print events
    window.onafterprint = function () {
        window.history.back();
    };

    // Trigger the process immediately when the page loads
    window.onload = function () {
        doPrintAndReturn();
    }
</script>

</body>
</html>
