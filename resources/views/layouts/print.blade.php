<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>
    <!-- If you need Tailwind or specific styles, link them here -->
</head>
<style>
    * {
        margin: 0;
        padding: 0;
        text-decoration: none;
    }
</style>
@stack('styles')
<body>

{{ $slot }}

@stack('scripts')
</body>
</html>
