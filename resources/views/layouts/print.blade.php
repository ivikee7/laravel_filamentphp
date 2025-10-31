<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- If you need Tailwind or specific styles, link them here -->
</head>
<body>
<!-- The content of your print-monthly-report view will be injected here -->
{{ $slot }}
</body>
</html>
