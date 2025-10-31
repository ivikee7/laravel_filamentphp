<x-filament-panels::page>
    <style>
        @media print {
            /* ... (your existing print styles) ... */
            /* Hide the Filament modal backdrop and container */
            div {
                visibility: hidden !important;
            }
            .fi-ta-selection-cell, .fi-ta-selection-cell{
                visibility: hidden !important;
            }
            * {
                margin:0;
                padding:0;
                position: relative;
            }
            table, tr, td{
                border: 1px solid black;
                color: black;
            }

            /* Make sure the main content is visible and takes priority over hidden modals */
            .fi-ta-table {
                visibility: visible !important;
                /*display: block !important;*/
            }
        }
    </style>

    <!-- This renders your table defined in the PHP class -->
    {{ $this->table }}
</x-filament-panels::page>
