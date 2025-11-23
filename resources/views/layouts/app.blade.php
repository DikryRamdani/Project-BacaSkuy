<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'BacaSkuy') }}</title>

    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>

<body class="bg-gray-100 text-gray-900 min-h-screen flex flex-col">
    @if (!request()->routeIs('chapter.reader'))
        <x-navbar id="main-navbar" />
    @endif

    <main class="py-6 flex-1 pb-20 md:pb-6">
        @yield('content')
    </main>

    @if (!request()->routeIs('chapter.reader'))
        <x-footer id="main-footer" />
    @endif

    @stack('scripts')
</body>

</html>
