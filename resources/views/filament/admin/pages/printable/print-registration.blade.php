<!DOCTYPE html>
<html>
<head>
    <title>Print ID Cards</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QRCode.js for QR code generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode.js/1.0.0/qrcode.min.js"></script>
    <style>
        /* Your ID card styling from before */
        .id-card-container {
            width: 2.125in;
            height: 3.375in;
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

        .brand-logo {
            height: 40px;
            margin-right: 10px;
        }

        .brand-name {
            height: 40px;
            margin-right: 0;
        }

        .brand-address {
            font-size: 0.49rem;
            color: black;
        }

        .brand-contact-info {
            font-size: 0.6rem;
            color: black;
        }

        .id-card-container .brand-address, .brand-contact-info {
            font-weight: bold;
        }

        hr {
            border: 2px solid green;
            position: absolute; /* Position relative to the .id-card-container */
            left: 0; /* Align to the left edge of the .id-card-container */
            right: 0; /* Align to the right edge of the .id-card-container */
            margin-top: 0; /* Reset default hr margins */
            margin-bottom: 0; /* Reset default hr margins */
        }

        .id-card-student-info-section {

        }

        .id-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            padding: 2px 8px;
            background-color: #edf2f7; /* Light gray background */
            border-radius: 6px;
            font-size: 0.7rem; /* text-sm */
            font-weight: bold; /* semibold */
            color: #2d3748; /* Tailwind gray-800 */
        }

        .photo-placeholder {
            width: 90px;
            height: 90px;
            border: 1px solid #cbd5e0; /* Tailwind gray-300 */
            background-color: #f0f4f8; /* Lighter background */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.75rem;
            color: #a0aec0; /* Tailwind gray-500 */
        }

        .qr-code-area {
            width: 80px;
            height: 80px;
            display: flex;
            justify-content: center;
            align-items: center;
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


<script>
    // Automatically trigger print dialog when the page loads
    // window.onload = function() {
    //     window.print();
    // };
</script>
</body>
</html>
