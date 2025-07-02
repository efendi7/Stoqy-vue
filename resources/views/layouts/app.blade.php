<!DOCTYPE html>
{{-- Add dark class when dark mode is active --}}
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Stockify')</title>

    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')

    {{-- Dark Mode Script Init (Important: Must be before body to prevent FOUC) --}}
    <script>
        // On page load or when changing themes, best to add inline in `head` to avoid FOUC
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
</head>
<body class="font-sans antialiased h-full bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-300">
    @php
        $setting = App\Models\Setting::first();
        $pendingRequests = \App\Models\RoleRequest::where('status', 'pending')->count();
    @endphp

    {{-- Include Navbar --}}
    @include('partials.navbar', ['setting' => $setting, 'pendingRequests' => $pendingRequests])

    {{-- Include Sidebar --}}
    @include('partials.sidebar', ['setting' => $setting, 'pendingRequests' => $pendingRequests])

    {{-- Main Content Area --}}
    <div class="lg:ml-64 pt-16 min-h-screen">
        <main>
            @yield('content')
        </main>
    </div>

    @stack('scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Sidebar Toggle for Mobile
            const sidebarToggleButton = document.getElementById("sidebar-toggle-button");
            const sidebarCloseButton = document.getElementById("sidebar-close-button");
            const sidebar = document.getElementById("sidebar");

            sidebarToggleButton?.addEventListener("click", function() {
                sidebar.classList.remove("-translate-x-full");
                sidebar.classList.add("translate-x-0");
            });

            sidebarCloseButton?.addEventListener("click", function() {
                sidebar.classList.remove("translate-x-0");
                sidebar.classList.add("-translate-x-full");
            });

            // Close sidebar if open on mobile and clicked outside
            document.addEventListener("click", function (event) {
                if (window.innerWidth < 1024 &&
                    !sidebar.classList.contains("-translate-x-full") &&
                    !sidebar.contains(event.target) &&
                    !sidebarToggleButton.contains(event.target))
                {
                    sidebar.classList.remove("translate-x-0");
                    sidebar.classList.add("-translate-x-full");
                }
            });

            // Dark Mode Toggle Logic
            const themeToggleBtn = document.getElementById('theme-toggle');
            const htmlElement = document.documentElement; // This is the <html> tag

            // Set initial icon based on current theme
            function setToggleIcon() {
                if (htmlElement.classList.contains('dark')) {
                    themeToggleBtn.innerHTML = '<i class="fas fa-sun"></i>'; // Sun icon for dark mode
                } else {
                    themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>'; // Moon icon for light mode
                }
            }

            // Set initial icon on load
            setToggleIcon();

            themeToggleBtn?.addEventListener('click', () => {
                if (htmlElement.classList.contains('dark')) {
                    htmlElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    htmlElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
                setToggleIcon(); // Update icon after theme change
            });
        });
    </script>
</body>
</html>