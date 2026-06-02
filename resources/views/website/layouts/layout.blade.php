<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Clean and modern school website.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:wght@700&display=swap" rel="stylesheet">
    <style>
        :root {
            --c-primary: #1d4ed8;
            --c-primary-dark: #1e3a8a;
            --c-bg: #f8fafc;
            --c-card: #ffffff;
            --c-text: #0f172a;
            --c-muted: #64748b;
            --c-line: #dbe3ee;
            --c-footer: #0b1220;
            --radius: 12px;
            --shadow: 0 10px 28px rgba(15, 23, 42, 0.08);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        html { overflow-x: hidden; }
        body { font-family: 'Inter', sans-serif; background: var(--c-bg); color: var(--c-text); line-height: 1.6; }
        a { color: inherit; text-decoration: none; }
        .container { max-width: 1140px; width: 100%; padding: 0 16px; margin: 0 auto; }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            border: 1px solid transparent;
            padding: 0.7rem 1rem;
            font-size: 0.88rem;
            font-weight: 700;
            transition: 0.2s ease;
        }

        .btn-primary { background: var(--c-primary); color: #fff; }
        .btn-primary:hover { background: var(--c-primary-dark); }

        .content-wrap {
            min-height: 70px;
            padding: 1rem 0;
        }

        .page-card {
            background: var(--c-card);
            border: 1px solid var(--c-line);
            border-radius: 14px;
            box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
            padding: 1.1rem;
        }

        .page-card h1,
        .page-card h2,
        .page-card h3 {
            color: var(--c-primary-dark);
            font-family: 'Merriweather', serif;
            margin: 0.95rem 0 0.5rem;
        }

        .page-card p,
        .page-card li,
        .page-card td { color: #334155; }

        .page-card ul,
        .page-card ol { padding-left: 1.1rem; margin-bottom: 0.8rem; }
        @media (max-width: 900px) {
            .container {
                width: min(1140px, calc(100vw - 24px));
            }

            .content-wrap {
                padding: 0.85rem 0;
            }
        }
    </style>
    @stack('styles')
</head>
<body>

@include('website.components.top-bar')
@include('website.components.navbar')

<main class="content-wrap">
    <div class="container">
        @yield('content')
    </div>
</main>

@include('website.components.footer')

@stack('scripts')
</body>
</html>
