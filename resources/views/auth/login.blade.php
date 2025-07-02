@extends('auth.layouts.auth')

@section('content')
<div class="min-h-screen flex items-center justify-center relative overflow-hidden bg-gray-50 dark:bg-dark">
    {{-- Background elements for visual flair (adjust z-index if needed for complex backgrounds) --}}
    <div class="hero-gradient absolute inset-0 z-0"></div>
    <div class="particles-container absolute inset-0 z-0" id="particles-js"></div>

    {{-- Dark Mode Toggle --}}
    <div x-data="{ darkMode: localStorage.getItem('theme') === 'dark' }" x-init="$watch('darkMode', val => localStorage.setItem('theme', val ? 'dark' : 'light')); darkMode ? document.documentElement.classList.add('dark') : document.documentElement.classList.remove('dark');"
        :class="{ 'dark': darkMode }" class="fixed top-8 right-8 z-50">
        <button @click="darkMode = !darkMode" class="p-3 rounded-full bg-gray-200 dark:bg-dark-light text-gray-800 dark:text-gray-300 shadow-lg transition-colors duration-300">
            <template x-if="darkMode">
                {{-- Moon Icon for Dark Mode --}}
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                </svg>
            </template>
            <template x-if="!darkMode">
                {{-- Sun Icon for Light Mode --}}
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h1M2 12h1m15.364 6.364l-.707.707M6.343 6.343l-.707-.707m12.728 0l-.707-.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </template>
        </button>
    </div>

    {{-- Main content area: Split into two columns --}}
    <div class="relative z-10 w-full max-w-6xl mx-auto flex flex-col lg:flex-row rounded-2xl overflow-hidden shadow-2xl">

       {{-- Left Column: Hero Image --}}
<div class="hidden lg:flex lg:w-1/2 relative overflow-hidden bg-gradient-to-br from-purple-600 via-indigo-600 to-blue-500">
    <div class="flex flex-col items-center justify-center p-8 relative z-10">
        <!-- Gambar -->
        <img src="{{ asset('img/Ordering Groceries Online.png') }}"
            alt="Ordering Groceries Online"
            class="w-2/3 h-auto mb-6">

        <!-- Teks -->
        <div class="text-center text-white max-w-lg">
            <h2 class="text-3xl font-bold mb-2">Manage Your Inventory</h2>
            <p class="text-lg opacity-90">Streamline your business operations with our comprehensive inventory management system.</p>
        </div>
    </div>
</div>



        {{-- Right Column: Login Form --}}
        <div class="w-full lg:w-1/2 bg-white dark:bg-dark-light glass-effect p-8 lg:p-12 animate-fade-in-up">
            <div class="flex flex-col items-center mb-6">
                {{-- Login title with logo --}}
                <div class="flex items-center justify-center mb-6">
    <h1 class="text-2xl mb-3 font-bold text-gray-900 dark:text-white mr-3">Login to</h1>
    <img src="{{ asset('img/stoqy_black_transparent.png') }}" alt="Stoqy Logo" class="h-12 w-auto object-contain">
</div>


            {{-- Success Message --}}
            @if(session('success'))
                <div class="bg-green-100 dark:bg-green-800 text-green-700 dark:text-green-200 p-4 rounded-lg mb-4 shadow-md text-sm">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error Messages --}}
            @if ($errors->any())
                <div class="bg-red-100 dark:bg-red-800 text-red-700 dark:text-red-200 p-4 rounded-lg mb-4 shadow-md text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('login') }}" method="POST" class="space-y-6">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email Address</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-dark text-gray-900 dark:text-white placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                        required autocomplete="email" autofocus>
                </div>
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-dark text-gray-900 dark:text-white placeholder-gray-400 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 shadow-sm"
                        required autocomplete="current-password">
                </div>

                <div class="flex items-center justify-between text-sm">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600">
                        <label for="remember_me" class="ml-2 block text-gray-700 dark:text-gray-300">Remember me</label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Forgot password?</a>
                    @endif
                </div>

                <button type="submit"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-all transform hover:scale-105 shadow-lg">
                    Login
                </button>

                <div class="text-center text-gray-700 dark:text-gray-300 text-sm mt-4">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:underline dark:text-blue-400 dark:hover:text-blue-300 transition-colors">Register here</a>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

{{-- PENTING: Pindahkan semua style ke resources/css/app.css dan jalankan npm run dev/build --}}
{{-- Atau pastikan style ini dimuat di layouts/app.blade.php. --}}
{{-- Untuk demo, saya tetap sertakan di sini agar Anda bisa melihat semua kode yang relevan. --}}
@section('styles')
<style>
    body {
        background-color: #f8fafc; /* Default light background */
    }
    html.dark body {
        background-color: #0f172a; /* Dark background */
    }

    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }

    @keyframes slideUp {
        from { transform: translateY(30px); opacity: 0; }
        to { transform: translateY(0); opacity: 1; }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .gradient-bg { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }

    .glass-effect {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    html.dark .glass-effect {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(30, 41, 59, 0.7);
    }

    .text-gradient {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .hero-gradient {
        background: radial-gradient(ellipse at center, rgba(59, 130, 246, 0.15) 0%, transparent 70%);
    }
    html.dark .hero-gradient {
        background: radial-gradient(ellipse at center, rgba(30, 60, 150, 0.25) 0%, transparent 70%);
    }

    .feature-icon { background: linear-gradient(135deg, #3b82f6, #1e40af); }

    .animate-fade-in-up { animation: slideUp 0.8s ease-out forwards; }

    .particles-container {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        overflow: hidden; pointer-events: none; z-index: -1;
    }
    .particle {
        position: absolute; background-color: rgba(59, 130, 246, 0.6);
        border-radius: 50%; animation: moveParticles linear infinite;
    }
    html.dark .particle { background-color: rgba(118, 75, 162, 0.5); }

    .hero-background {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 30%, #f093fb 60%, #f5576c 100%);
        background-position: center;
    }
    html.dark .hero-background {
        background: linear-gradient(135deg, #4c1d95 0%, #581c87 30%, #7c3aed 60%, #8b5cf6 100%);
    }

    @keyframes moveParticles {
        0% { transform: translate(0, 0); opacity: 0; }
        50% { opacity: 1; }
        100% { transform: translate(var(--x-end), var(--y-end)); opacity: 0; }
    }
</style>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const particlesContainer = document.getElementById('particles-js');
        if (!particlesContainer) return;

        // Function to create particles
        const createParticles = (num, colors) => {
            particlesContainer.innerHTML = ''; // Clear existing particles
            for (let i = 0; i < num; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                const size = Math.random() * 8 + 4;
                const x = Math.random() * 100;
                const y = Math.random() * 100;
                const duration = Math.random() * 10 + 5;
                const delay = Math.random() * 5;
                const endX = (Math.random() - 0.5) * 200;
                const endY = (Math.random() - 0.5) * 200;
                const color = colors[Math.floor(Math.random() * colors.length)];
                particle.style.cssText = `
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}%;
                    top: ${y}%;
                    animation-duration: ${duration}s;
                    animation-delay: ${delay}s;
                    background-color: ${color};
                    --x-end: ${endX}px;
                    --y-end: ${endY}px;
                `;
                particlesContainer.appendChild(particle);
            }
        };

        const colorsLight = ['rgba(59, 130, 246, 0.6)', 'rgba(118, 75, 162, 0.6)', 'rgba(245, 158, 11, 0.6)'];
        const colorsDark = ['rgba(59, 130, 246, 0.4)', 'rgba(118, 75, 162, 0.4)', 'rgba(245, 158, 11, 0.4)'];

        // Initial particle generation
        createParticles(30, document.documentElement.classList.contains('dark') ? colorsDark : colorsLight);

        // Re-generate particles when dark mode is toggled
        window.Alpine.effect(() => {
            const isDarkMode = document.documentElement.classList.contains('dark');
            const newColors = isDarkMode ? colorsDark : colorsLight;
            createParticles(30, newColors); // Re-create particles with new colors
        });
    });

    // Initial dark mode preference on page load (ensure this runs early)
    document.addEventListener('DOMContentLoaded', () => {
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark')
        } else {
            document.documentElement.classList.remove('dark')
        }
    });
</script>
@endpush