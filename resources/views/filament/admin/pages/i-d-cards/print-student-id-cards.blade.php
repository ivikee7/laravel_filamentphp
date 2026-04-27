<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Print ID Cards</title>
    <style>
        /* Clean custom card styles (no Tailwind) */
        :root {
            --card-width: 2.125in;
            --card-height: 3.375in;
            --card-bg: #fff;
            --card-border: #e6edf3;
        }

        html, body {
            height: 100%;
        }

        body {
            margin: 10px;
            background: #f8fafc;
            font-family: Arial, Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .sheet {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: flex-start
        }

        .card {
            /* resolved explicit fallbacks to satisfy static analysis tools */
            width: 2.125in; /* fallback for --card-width */
            height: 3.375in; /* fallback for --card-height */
            background: #fff; /* fallback for --card-bg */
            border: 1px solid #e6edf3; /* fallback for --card-border */
            border-radius: 8px;
            padding: 10px;
            box-sizing: border-box;
            display: grid;
            /* let header size to content, body expand, footer fixed */
            grid-template-rows: auto 1fr auto;
            gap: 6px;
            overflow: visible;
            position: relative; /* allow absolute footer inside card */
        }

        /* Header (20%) */
        .header {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 4px
        }

        .header .brand {
            display: flex;
            align-items: center;
            gap: 8px
        }

        .header .brand img.logo {
            width: 40px;
            height: 40px;
            object-fit: contain
        }

        .header .brand img.name {
            height: 36px;
            object-fit: contain
        }

        .header .address {
            font-size: 7px;
            font-weight: 700;
            margin: 0;
            text-align: center
        }

        .header .contact {
            font-size: 10px;
            font-weight: 700;
            margin: 0;
            text-align: center
        }

        /* full-bleed divider: extends to card edges ignoring the card padding */
        .divider {
            height: 2px;
            background: #085330;
            width: calc(100% + 20px); /* card padding left+right = 20px */
            margin: 0 -10px; /* pull it outside the card padding */
        }

        /* Body (80%) */
        .body {
            overflow: visible
        }
        .body .meta-row{
            background: #f3f4f6;
            border-radius: 8px;
            padding: 6px;
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap:6px;
            /* keep both items on a single line */
            flex-wrap:nowrap;
        }
        /* prevent the SRCS / Session text from wrapping to next line */
        .body .meta-row > * { white-space:nowrap;
            font-size: 9px; }

        .body .top-row {
            display: flex;
            gap: 8px;
            align-items: center; /* center items so photo and qr align */
            justify-content: space-between;
            overflow: hidden;
            min-height: 90px; /* ensure enough height for 90x90 children */
        }

        .photo {
            width: 80px;
            height: 80px;
            border: 1px solid #e6edf3;
            border-radius: 6px;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* no photo-wrap: meta-row shown above top-row */
        .photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block; /* prevent inline gap/overflow */
            max-width: 100%;
            max-height: 100%;
        }

        .qrcode {
            width: 80px; /* match photo size */
            height: 80px; /* match photo size */
            border: 1px solid #e6edf3; /* match photo border */
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: #fff;
            box-sizing: border-box;
        }

        /* ensure QR svg/img fits its parent and cannot overflow */
        .qrcode svg,
        .qrcode img {
            width: 100% !important;
            height: 100% !important;
            display: block;
            object-fit: contain;
        }

        /* qr-overlay removed */

        /* ensure photo and qrcode respect their box sizing */
        .photo, .qrcode { box-sizing: border-box }

        .details {
            margin-top: 6px;
            font-size: 11px
        }

        /* student name style (red) */
        .student-name {
            text-align: center;
            font-weight: 700;
            font-size: 13px;
            color: #b91c1c; /* red */
        }

        .details p {
            margin: 2px 0
        }

        /* Footer (signature) - fixed inside the card and above other text */
        .footer {
            position: absolute;
            right: 10px;
            bottom: 10px;
            left: 10px;
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: center;
            z-index: 20; /* make sure it sits on top of body text */
            pointer-events: auto;
        }

        .signature img {
            height: 44px;
            object-fit: contain
        }

        .signature .label {
            font-size: 10px;
            color: #6b7280
        }

        @media print {
            body {
                margin: 0
            }

            .card {
                box-shadow: none
            }
        }
    </style>
</head>
<body>
<div class="sheet">
    @foreach($records as $record)
        @php
            // Prefer an explicit check to avoid catching unrelated exceptions.
            // Route::has() returns true when the named route is registered.
            // This is clearer than a broad try/catch and avoids hiding other problems.
            if (\Illuminate\Support\Facades\Route::has('filament.admin.pages.id-cards')) {
                $qrData = route('filament.admin.pages.id-cards', ['user' => $record->id]);
            } else {
                $qrData = url('/admin/id-cards/' . $record->id);
            }
        @endphp

        <div class="card" role="region" aria-label="ID card for {{ $record->name }}">
            <div class="header">
                <div class="brand">
                    <img src="{{ asset('storage/media/logo_50.png') }}" alt="logo" class="logo">
                    <img src="{{ asset('storage/media/logo_name_150.png') }}" alt="name" class="name">
                </div>
                <div class="address">Bhogipur, Near Shahpur, Jaganpura, Patna-804453</div>
                <div class="contact">Helpline: +91 8873002602 / 03</div>
                <div class="divider" aria-hidden="true"></div>
            </div>

            <div class="body">
                <div class="meta-row"
                     style="display:flex;justify-content:space-between;align-items:center;font-size:11px;font-weight:700;margin-bottom:6px">
                    <div class="meta-srcs">SRCS#: {{ $record->id }}</div> {{ __(' | ') }}
                    <div class="meta-session">
                        Session: {{ $record->student->classAssignment->academicYear->name ?? '-' }}</div>
                </div>

                <div class="top-row">
                    <div class="photo">
                        @if(!empty($record->avatar))
                            <img src="{{ asset('storage/' . ltrim($record->avatar, '/')) }}" alt="{{ $record->name }}">
                        @else
                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode($record->name) }}&background=fff&color=333&size=256"
                                alt="{{ $record->name }}">
                        @endif
                    </div>
                    <div class="qrcode">
                        {!! QrCode::size(80)->generate($qrData) !!}
                    </div>
                </div>

                <div class="details">
                        <div class="student-name">{{ strtoupper($record->name) }}</div>
                    <p><strong>Class:</strong> {{ $record->student->classAssignment->class->className->name ?? '-' }}
                    </p>
                    <p><strong>Sec:</strong> {{ $record->student->classAssignment->section->name ?? '-' }}</p>
                    <p><strong>Mob:</strong> {{ $record->primary_contact_number ?? '-' }}</p>
                </div>
            </div>

            <div class="footer">
                <div class="signature"><img src="{{ asset('storage/media/signature/principal_signature.png') }}"
                                            alt="sig"></div>
                <div class="signature"><span class="label">Signature</span></div>
            </div>
        </div>
    @endforeach
</div>

</body>
</html>
