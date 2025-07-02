<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Stockify')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ==================== PASTIKAN BARIS INI ADA ==================== --}}
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    {{-- ================================================================ --}}

    @stack('styles')

    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased h-full bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    
    @include('partials.navbar')
    @include('partials.sidebar')

    <div class="lg:ml-64 pt-16 min-h-screen">
        <main>
            @yield('content')
        </main>
    </div>

    {{-- Pustaka Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    {{-- ==================== PASTIKAN BARIS INI ADA ==================== --}}
    {{-- 'defer' penting agar script ini dieksekusi setelah HTML selesai di-parse --}}
    <script src="{{ asset('js/dashboard.js') }}" defer></script>
    {{-- ================================================================ --}}

    @stack('scripts')
</body>
</html>