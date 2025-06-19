<!DOCTYPE html>
<html>
<head>
    <title>Print ID Cards</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- QRCode.js for QR code generation -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcode.js/1.0.0/qrcode.min.js"></script>
    <style>
        .id-card-container .brand-logo,.brand-logo,.brand-name,.brand-affiliation,.brand-address,.brand-contact-info{
            margin: 0;
            padding: 0;
            text-align: center;
            font-weight: bold;
        }
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
        .id-card-container .brand-name,.brand-affiliation,.brand-address,.brand-contact-info{
            line-height: normal;
        }
        .brand-logo {height: 40px;margin-right: 10px;}
        .brand-name {height: 40px;margin-right: 0;}
        .brand-affiliation {
            font-size: 0.59rem; /* text-xs */
            color: red; /* Tailwind gray-700 */
        }
        .brand-address {
            font-size: 0.49rem; /* smaller text */
            color: black;
        }
        .brand-contact-info {
            font-size: 0.7rem; /* smaller text */
            color: black;
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
        .id-card-student-name{

        }
        /* Add any other specific print styles */
        @media print {
            body { margin: 0; padding: 0; }
            .id-card-container {
                box-shadow: none; /* Remove shadows for print */
                border: 1px solid #000; /* Ensure borders are visible */
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
                <p class="brand-affiliation">Affiliated to C.B.S.E. Delhi, Aff. No.-330653</p>
                <p class="brand-address">Bhogipur, Near Shahpur, Jaganpura, Patna-804453</p>
                <p class="brand-contact-info">Helpline No.+918873002602/03</p>
            </div>
        </div>
        <hr>

        <!-- ID/Session Section -->
        <div class="id-section">
            <span>SRCS/ 1</span>
            <span>Session : {{$record->currentStudent->currentClassAssignment->academicYear->name}}</span>
        </div>

        <!-- Photo and QR Code Section -->
        <div class="flex justify-around items-center my-1">
            <div class="photo-placeholder rounded-lg overflow-hidden">
                <!-- Replace with actual user photo if available -->
                <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}" alt="User Photo"
                     class="w-full h-full object-cover">
            </div>
{{--            @dd($record->currentStudent->currentClassAssignment->class->className)--}}
            <div class="qr-code-area" id="qrcode">
                <div class="bg-white p-2 rounded">
                    {!! QrCode::size(80)->generate(route('filament.admin.pages.id-cards.{record}', ['record' => $record->id])) !!}
                </div>
            </div>
        </div>

        <div class="student-details">
            <p class="text-center font-bold whitespace-nowrap id-card-student-name">{{ $record->name }}</p>
            <p><strong class="font-semibold">Class :</strong> {{ $record->currentStudent->currentClassAssignment->class->className->name ?? 'NA' }}</p>
            <p><strong class="font-semibold">Sec :</strong> {{ $record->currentStudent->currentClassAssignment->section->name ?? 'NA' }}</p>
            <p><strong class="font-semibold">Mob :</strong> {{ $record->primary_contact_number ?? '' }} / {{$record->primary_contact_number ?? ''}}</p>
        </div>
        <div class="id-card-footer">
            <p>Signature</p>
        </div>
    </div>
@endforeach

<script>
    // Automatically trigger print dialog when the page loads
    // window.onload = function() {
    //     window.print();
    // };
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameElements = document.querySelectorAll('.id-card-student-name');

        nameElements.forEach(nameElement => {
            const originalFontSize = parseFloat(window.getComputedStyle(nameElement).fontSize);
            const parentElement = nameElement.parentNode; // The parent element (e.g., a div within your ID card)

            // Ensure the parent element has a defined width, e.g., via CSS
            // If the parent is the .id-card-container, you'd get its width.
            // For robust measurement, consider a wrapper around just the name if needed.
            const containerWidth = parentElement.offsetWidth;

            function adjustFontSize() {
                // Reset to original size for accurate measurement
                nameElement.style.fontSize = originalFontSize + 'px';

                // Check if text overflows the container
                if (nameElement.scrollWidth > containerWidth) {
                    let currentFontSize = originalFontSize;
                    // Shrink font size until it fits or reaches a minimum (e.g., 8px)
                    while (nameElement.scrollWidth > containerWidth && currentFontSize > 8) {
                        currentFontSize -= 0.5; // Decrease by 0.5px for smoother adjustment
                        nameElement.style.fontSize = currentFontSize + 'px';
                    }
                }
            }

            // Call adjustment on load
            adjustFontSize();

            // Optional: Use ResizeObserver for dynamic adjustment if the parent container's size can change
            // This is more efficient than listening to window.resize for specific element changes.
            const resizeObserver = new ResizeObserver(entries => {
                for (let entry of entries) {
                    if (entry.target === parentElement) {
                        adjustFontSize();
                    }
                }
            });
            resizeObserver.observe(parentElement); // Observe the parent element for width changes
        });
    });
</script>
</body>
</html>
