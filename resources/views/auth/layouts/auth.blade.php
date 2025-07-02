<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    {{-- Jika Anda menggunakan Laravel Mix: --}}
    {{-- <link rel="stylesheet" href="{{ mix('css/app.css') }}"> --}}
    {{-- Jika Anda menggunakan Vite: --}}
    @vite(['resources/css/app.css'])

    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    {{-- CUSTOM TAILWIND CONFIG (If not in tailwind.config.js and compiled) --}}
    {{-- IDEALNYA, INI SEMUA SUDAH DI TAILWIND.CONFIG.JS DAN DIKOMPILASI --}}
    <script>
        tailwind.config = {
            darkMode: 'class', // Make sure this is in your tailwind.config.js
            theme: {
                extend: {
                    colors: {
                        primary: '#3b82f6',
                        secondary: '#1e40af',
                        accent: '#f59e0b',
                        dark: '#0f172a',
                        'dark-light': '#1e293b'
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'slide-up': 'slideUp 0.5s ease-out',
                        'fade-in': 'fadeIn 0.6s ease-out',
                        'fade-in-up': 'slideUp 0.8s ease-out forwards',
                        'bounce-slow': 'bounce 2s infinite',
                    },
                    keyframes: {
                        slideUp: { '0%': { transform: 'translateY(30px)', opacity: '0' }, '100%': { transform: 'translateY(0)', opacity: '1' } },
                        fadeIn: { '0%': { opacity: '0' }, '100%': { opacity: '1' } },
                        bounce: { '0%, 100%': { transform: 'translateY(-10%)', 'animation-timing-function': 'cubic-bezier(0.8, 0, 1, 1)' }, '50%': { transform: 'none', 'animation-timing-function': 'cubic-bezier(0, 0, 0.2, 1)' } }
                    }
                }
            }
        }
    </script>
    {{-- END CUSTOM TAILWIND CONFIG --}}

    {{-- Global Styles (if not part of app.css via Tailwind directives) --}}
    {{-- Ini juga idealnya harus di resources/css/app.css --}}
    <style>
        body { background-color: #f8fafc; }
        html.dark body { background-color: #0f172a; }

        .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .glass-effect { background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2); }
        html.dark .glass-effect { background: rgba(15, 23, 42, 0.5); border: 1px solid rgba(30, 41, 59, 0.7); }

        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .hero-gradient { background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.15) 0%, transparent 70%); }
        html.dark .hero-gradient { background: radial-gradient(ellipse at center, rgba(30, 60, 150, 0.25) 0%, transparent 70%); }

        .feature-icon { background: linear-gradient(135deg, #3b82f6, #1e40af); }

        .animate-on-scroll { opacity: 0; transform: translateY(30px); transition: opacity 0.8s ease-out, transform 0.8s ease-out; }
        .animate-on-scroll.is-visible { opacity: 1; transform: translateY(0); }

        .particles-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: -1; }
        .particle { position: absolute; background-color: rgba(59, 130, 246, 0.6); border-radius: 50%; animation: moveParticles linear infinite; }
        html.dark .particle { background-color: rgba(118, 75, 162, 0.5); }

        @keyframes moveParticles {
            0% { transform: translate(0, 0); opacity: 0; }
            50% { opacity: 1; }
            100% { transform: translate(var(--x-end), var(--y-end)); opacity: 0; }
        }
    </style>
    {{-- END Global Styles --}}

    @stack('styles') {{-- Untuk style spesifik halaman jika ada --}}
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 dark:bg-dark-light">
        @yield('content') {{-- Ini adalah tempat konten dari halaman yang meng-extend layout ini --}}
    </div>

    @stack('scripts') {{-- Untuk JavaScript spesifik halaman --}}
</body>
</html>