<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ID Card - {{ $user->name ?? 'User' }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QRCode.js for QR code generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode.js/1.0.0/qrcode.min.js"></script>

    <style>
        /* Custom styles for the ID card layout */
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            background-color: #f7fafc; /* Tailwind gray-100 */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }

        .id-card-container {
            width: 300px; /* Fixed width for an ID card */
            height: 480px; /* Fixed height for an ID card */
            background-color: white;
            border: 1px solid #e2e8f0; /* Tailwind gray-200 */
            border-radius: 12px; /* Rounded corners */
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); /* Shadow */
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative; /* For absolute positioning of some elements */
            overflow: hidden; /* Ensure content stays within bounds */
        }

        .header-logo {
            width: 50px;
            height: auto;
            margin-right: 10px;
        }

        .school-name {
            font-size: 1.25rem; /* text-xl */
            font-weight: bold;
            color: #1a6200; /* Dark green from the image, adjust as needed */
            line-height: 1.2;
        }

        .affiliation {
            font-size: 0.75rem; /* text-xs */
            color: #4a5568; /* Tailwind gray-700 */
            margin-top: 5px;
        }

        .contact-info {
            font-size: 0.7rem; /* smaller text */
            color: #4a5568;
            margin-top: 3px;
        }

        .id-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            padding: 8px 12px;
            background-color: #edf2f7; /* Light gray background */
            border-radius: 6px;
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* semibold */
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
        }

        .qr-code-area {
            width: 100px;
            height: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .student-details {
            margin-top: 5px;
            text-align: left;
            padding-left: 10px;
        }

        .student-details p {
            margin-bottom: 4px;
            font-size: 0.9rem;
            color: #2d3748;
        }

        .student-details strong {
            font-weight: 600;
        }

        .student-name {
            font-size: 1.1rem;
            font-weight: bold;
            color: #e53e3e; /* Red color from image */
            margin-bottom: 8px;
            text-transform: uppercase;
            text-align: center;
        }

        .principal-signature {
            text-align: right;
            margin-top: 20px;
            font-style: italic; /* for cursive look */
            color: #4a5568;
            font-size: 0.8rem;
            font-family: ' cursive'; /* Generic cursive font */
            padding-right: 10px;
        }

        /* Styles for printing */
        @media print {
            body {
                background-color: white !important;
                display: block; /* Allow normal flow for printing */
                padding: 0;
                margin: 0;
            }

            .id-card-container {
                width: 55mm; /* Standard ID card width */
                height: 85mm; /* Standard ID card height */
                border: 1px solid gray;
                box-shadow: none;
                page-break-after: always; /* Ensure each card is on a new page if printing multiple */
                margin: 0 auto; /* Center on page */
                border-radius: 10px;
                transform: scale(0.9); /* Slightly shrink for better fit on paper */
                transform-origin: top left;
                position: relative;
            }

            .header-logo {
                width: auto;
                height: 50px;
                /*margin-right: 10px;*/
            }

            /* Adjust font sizes for print clarity */
            .school-name {
                font-size: 1.1rem;
            }

            .affiliation, .contact-info {
                font-size: 0.5rem;
                margin: 0;
                padding: 0;
            }

            .id-section {
                font-size: 0.8rem;
                padding: 6px 10px;
            }

            .student-name {
                font-size: 1rem;
            }

            .student-details p {
                font-size: 0.85rem;
            }

            .principal-signature {
                font-size: 0.75rem;
            }
        }
    </style>
</head>
<body onload="window.print()">
<div class="id-card-container">
    <!-- Header Section -->
    <div class="flex items-center justify-center">
        <img src="{{asset('logo_50.png')}}" alt="School Logo" class="header-logo rounded">
        <img src="{{asset('logo_name_150.png')}}" alt="School Name" class="header-logo">
    </div>
    <div class="flex items-center justify-center border-b pb-1 mb-1">
        <div class="text-center">
            <p class="affiliation">Affiliated to C.B.S.E. Delhi, Aff. No.-330653</p>
            <p class="contact-info">Bhogipur, Near Shahpur, Jaganpura, Patna-804453</p>
            <p class="contact-info">Helpline No.+918873002602/2603</p>
        </div>
    </div>

    <!-- ID/Session Section -->
    <div class="id-section">
        <span>SRCS/ {{ $user->id }}</span>
        <span>Session : 2025-26</span>
    </div>

    <!-- Photo and QR Code Section -->
    <div class="flex justify-around items-center my-2">
        <div class="photo-placeholder rounded-lg overflow-hidden">
            <!-- Replace with actual user photo if available -->
            <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) }}" alt="User Photo"
                 class="w-full h-full object-cover">
        </div>
        <div class="qr-code-area" id="qrcode">
            <div class="bg-white p-2 rounded">
                {!! QrCode::size(100)->generate(route('filament.admin.pages.id-cards.{record}', ['record' => $user->id])) !!}
            </div>
        </div>
    </div>

    <!-- Student Details Section -->
    <div class="student-details flex-grow">
        <p class="student-name">{{ $user->name ?? 'AVNI KUMARI' }}</p>
        <p><strong class="font-semibold">Class :</strong> {{ $user->class ?? 'NA' }}</p>
        <p><strong class="font-semibold">Sec :</strong> {{ $user->section ?? 'NA' }}</p>
        <p><strong class="font-semibold">Mob :</strong> {{ $user->primary_contact_number ?? 'NA' }}</p>
    </div>

    <!-- Principal Signature Section -->
    <div class="principal-signature">
        <p>S.R.Luih</p>
        <p>Principal Sign.</p>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const userId = "{{ $user->id ?? '' }}"; // Get user ID from Blade
        // You might want to encode a URL like: `{{ url('/users/' . $user->id) }}`
        // Or just the user ID, depending on what the QR code should represent.
        const qrCodeData = `User ID: ${userId}, Name: {{ $user->name ?? '' }}`;

        if (document.getElementById("qrcode") && userId) {
            new QRCode(document.getElementById("qrcode"), {
                text: qrCodeData,
                width: 50,  // Slightly smaller to fit ID card
                height: 90, // Slightly smaller to fit ID card
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H // High error correction
            });
        }

        // Immediately trigger print when the page loads
        window.print(); // Already in body onload attribute
    });
</script>

</body>
</html>
