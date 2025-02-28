@php
    $setting = App\Models\Setting::first();
@endphp

<nav class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-purple-900 to-purple-700 shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo & App Name -->
            <div class="text-white font-bold text-lg">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 hover:scale-105 transition">
                <img src="{{ asset('storage/' . $setting->logo) }}?v={{ time() }}" class="w-8 h-8 rounded" alt="Logo">


                    <span>{{ $setting->app_name ?? config('app.name') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white transition">
                    <i class="fas fa-cube mr-1"></i> Produk
                </a>
                <a href="{{ route('suppliers.index') }}" class="text-gray-300 hover:text-white transition">
                    <i class="fas fa-truck mr-1"></i> Penyuplai
                </a>
                <a href="{{ route('stock_transactions.index') }}" class="text-gray-300 hover:text-white transition">
                    <i class="fas fa-exchange-alt mr-1"></i> Stok
                </a>
                <a href="{{ route('users.index') }}" class="text-gray-300 hover:text-white transition">
                    <i class="fas fa-users mr-1"></i> Pengguna
                </a>

                <!-- User Dropdown -->
                @auth
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 bg-purple-800 px-3 py-1 rounded-lg hover:bg-purple-700 transition focus:outline-none">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('img/logofenn.png') }}" alt="user photo">
                        <span class="text-white">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-300"></i>
                    </button>

                    <!-- Dropdown -->
                    <div id="user-dropdown" class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded-md hidden transition-all duration-200 transform origin-top-right scale-95">
                        <div class="px-4 py-3 border-b">
                            <span class="block text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                            <span class="block text-sm text-gray-500 truncate w-full overflow-hidden text-ellipsis">{{ Auth::user()->email }}</span>
                            <span class="block text-xs text-gray-400">Role: {{ ucfirst(Auth::user()->role) }}</span>
                        </div>
                        <ul class="py-2">
                            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a></li>
                            <li>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    Pengaturan
                                </a>
                            </li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sign out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const userMenuButton = document.getElementById("user-menu-button");
    const userDropdown = document.getElementById("user-dropdown");

    userMenuButton.addEventListener("click", function () {
        userDropdown.classList.toggle("hidden");
        userDropdown.classList.toggle("scale-100");
    });

    // Tutup dropdown jika klik di luar
    document.addEventListener("click", function (event) {
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add("hidden");
            userDropdown.classList.remove("scale-100");
        }
    });
});
</script>
