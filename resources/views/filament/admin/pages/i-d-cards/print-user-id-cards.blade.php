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

        .id-card-user-info-section {
            margin-top: 10px;
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
            width: 100px;
            height: 100px;
            border: 1px solid #cbd5e0; /* Tailwind gray-300 */
            background-color: #f0f4f8; /* Lighter background */
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.75rem;
            color: #a0aec0; /* Tailwind gray-500 */
            margin-top: 5px;
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
        <div class="flex items-center justify-center">
            <img src="{{asset('logo_50.png')}}" alt="School Logo" class="brand-logo">
            <img src="{{asset('logo_name_150.png')}}" alt="School Name" class="brand-name">
        </div>
        <div class="flex items-center justify-center">
            <div class="text-center">
                <p class="brand-address">Bhogipur, Near Shahpur, Jaganpura, Patna-804453</p>
                <p class="brand-contact-info">Helpline No.+918873002602/03</p>

            </div>
        </div>
        <hr>

        {{-- ID Card Student inso section --}}
        <div class="id-card-user-info-section">
            {{-- ID/Session Section--}}
            <div class="id-section">
                <span>SRCS/ {{$record->id}}</span>
            </div>

            <!-- Photo and QR Code Section -->
            <div class="flex justify-around items-center my-1">
                <div class="photo-placeholder rounded-lg overflow-hidden">
                    <!-- Replace with actual user photo if available -->
                    <img
                        src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}"
                        alt="User Photo"
                        class="w-full h-full object-cover">
                </div>
            </div>

            <div class="student-details">
                <p class="text-center font-bold whitespace-nowrap mt-1 mb-1 text-red-600">{{ $record->name }}</p>
                <p class="text-center font-bold whitespace-nowrap">{{ $record->roles->first()->name }}</p>
                <p class="text-center whitespace-nowrap">{{ $record->bloodGroup->name ?? '' }}</p>
                <p class="text-center whitespace-nowrap">{{ $record->date_of_birth ?? '' }}</p>
                <p class="text-center whitespace-nowrap">{{ $record->gSuite->email ?? '' }}</p>
            </div>
        </div>

        <!-- The Footer - Positioned Absolutely -->
        <div class="id-card-footer absolute bottom-0 right-0 flex flex-col justify-end items-end p-2">
            <img src="/signature/principal_signature.png" alt="Signature" class="h-8 w-auto mb-1 mr-2">
            <p class="text-xs text-gray-700">Signature</p>
        </div>
    </div>
@endforeach

<script>
    // Automatically trigger print dialog when the page loads
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>
