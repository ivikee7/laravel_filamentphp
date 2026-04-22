<!DOCTYPE html>
<html>
<head>
    <title>Print User ID Card - {{ $records->first()->name }}</title>
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
            background: white;
        }
        .brand-logo { height: 40px; margin-right: 8px; }
        .brand-name { height: 40px; }
        .brand-address { font-size: 0.49rem; color: black; font-weight: bold; }
        .brand-contact-info { font-size: 0.6rem; color: black; font-weight: bold; }

        hr {
            border: 1px solid green;
            margin: 2px 0;
            position: relative; /* Fixed from absolute to allow flow */
        }

        .id-section {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 5px;
            padding: 2px 8px;
            background-color: #edf2f7;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: bold;
            color: #2d3748;
        }

        .photo-placeholder {
            width: 90px;
            height: 90px;
            border: 1px solid #cbd5e0;
            background-color: #f0f4f8;
            margin: 8px auto;
            overflow: hidden;
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

        <div class="id-card-user-info-section">
            <!-- ID Section -->
            <div class="id-section">
                <span>Employee ID: {{ $record->id }}</span>
            </div>

            <!-- Photo Section -->
            <div class="photo-placeholder rounded-lg">
                <img src="{{ $record->avatar ? asset('storage/' . $record->avatar) : 'https://ui-avatars.com/api/?name=' . urlencode($record->name) }}"
                     alt="User Photo" class="w-full h-full object-cover">
            </div>

            <!-- User Details -->
            <div class="text-center px-1">
                <p class="font-bold truncate text-red-600 text-sm uppercase">{{ $record->name }}</p>
                <p class="font-bold text-gray-700 text-xs mb-1">
                    {{ $record->roles->first()->name ?? 'Staff' }}
                </p>

                <div class="text-[9px] leading-tight">
                    @if($record->bloodGroup)
                        <p><strong>Blood Group:</strong> {{ $record->bloodGroup->name }}</p>
                    @endif
                    @if($record->date_of_birth)
                        <p><strong>DOB:</strong> {{ \Carbon\Carbon::parse($record->date_of_birth)->format('d-m-Y') }}</p>
                    @endif
                    @if($record->gSuite)
                        <p class="truncate"><strong>Email:</strong> {{ $record->gSuite->email }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer / Signature -->
        <div class="absolute bottom-1 right-2 text-right">
            <img src="{{ asset('storage/media/signature/principal_signature.png') }}" class="h-6 w-auto ml-auto mb-0">
            <p style="font-size: 7px;" class="text-gray-700 font-bold uppercase">Authorized Signatory</p>
        </div>
    </div>
@endforeach

<script>
    window.onload = function() {
        setTimeout(() => {
            window.print();
        }, 500);
    };
</script>

</body>
</html>
