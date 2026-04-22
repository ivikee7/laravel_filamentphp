<!DOCTYPE html>
<html>
<head>
    <title>Print ID Card - {{ $records->first()->name }}</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
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
            float: left;
            page-break-inside: avoid;
            position: relative;
        }
        .brand-logo { height: 40px; margin-right: 10px; }
        .brand-name { height: 40px; }
        .brand-address { font-size: 0.49rem; color: black; font-weight: bold; }
        .brand-contact-info { font-size: 0.6rem; color: black; font-weight: bold; }

        hr {
            border: 1px solid green;
            margin: 2px 0;
        }

        .id-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 5px;
            padding: 2px 8px;
            background-color: #edf2f7;
            border-radius: 6px;
            font-size: 0.7rem;
            font-weight: bold;
            color: #2d3748;
        }

        .photo-placeholder {
            width: 80px;
            height: 80px;
            border: 1px solid #cbd5e0;
            background-color: #f0f4f8;
            overflow: hidden;
        }

        .qr-code-area {
            width: 70px;
            height: 70px;
        }

        @media print {
            body { margin: 0; padding: 0; }
            .id-card-container { box-shadow: none; border: 1px solid #ccc; }
        }
    </style>
</head>
<body>

@foreach($records as $record)
    <div class="id-card-container">
        <!-- Header -->
        <div class="flex items-center justify-center">
            <img src="{{ asset('storage/media/logo_50.png') }}" alt="Logo" class="brand-logo">
            <img src="{{ asset('storage/media/logo_name_150.png') }}" alt="Name" class="brand-name">
        </div>

        <div class="text-center">
            <p class="brand-address">Bhogipur, Near Shahpur, Jaganpura, Patna-804453</p>
            <p class="brand-contact-info">Helpline No.+918873002602/03</p>
        </div>

        <hr>

        <!-- ID/Session Section -->
        <div class="id-section">
            <span>SRCS/{{ $record->id }}</span>
            <span>Session: {{ $record->student->classAssignment->academicYear->name ?? 'N/A' }}</span>
        </div>

        <!-- Photo and QR Section -->
        <div class="flex justify-around items-center my-2">
            <div class="photo-placeholder rounded-lg">
                <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}"
                     class="w-full h-full object-cover">
            </div>
            <div class="qr-code-area">
                {{-- Fixed QR Code Logic --}}
                {!! QrCode::size(70)->generate(url("/verify-student/{$record->id}")) !!}
            </div>
        </div>

        <!-- Student Details -->
        <div class="px-2">
            <p class="text-center font-bold truncate text-red-600 text-xs mb-1">{{ strtoupper($record->name) }}</p>
            <p><strong class="font-semibold">Class :</strong> {{ $record->student->classAssignment->class->className->name ?? '' }} </p>
            <p><strong class="font-semibold">Sec :</strong> {{ $record->student->classAssignment->section->name ?? '' }}</p>
            <p><strong class="font-semibold">Mob :</strong> {{ $record->primary_contact_number ?? 'N/A' }}</p>
        </div>

        <!-- Footer / Signature -->
        <div class="absolute bottom-1 right-2 text-right">
            <img src="{{ asset('storage/media/signature/principal_signature.png') }}" class="h-6 w-auto ml-auto">
            <p style="font-size: 7px;" class="text-gray-700 font-bold">Principal Signature</p>
        </div>
    </div>
@endforeach

<script>
    window.onload = function() {
        // Delay slightly to ensure QR codes/images are rendered
        setTimeout(() => {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
