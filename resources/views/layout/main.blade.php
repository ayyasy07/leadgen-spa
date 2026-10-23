<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ session('color_scheme', 'default') }} {{ session('dark_mode') ? 'dark' : '' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? 'Icewall' }}</title>

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ mix('dist/css/app.css') }}">

    {{-- Popper + Tippy (WAJIB buat tooltip sidebar) --}}
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    @stack('head')
    @stack('styles')
</head>
<body class="app">
    @yield('content')

    {{-- JS Icewall --}}
    <script src="https://unpkg.com/feather-icons"></script>
    <script src="{{ mix('dist/js/app.js') }}"></script>

    @stack('script')
    @stack('scripts')
</body>
</html>