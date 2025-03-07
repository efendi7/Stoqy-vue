@php
    $setting = App\Models\Setting::first();
@endphp

<nav class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-purple-900 to-purple-700 shadow-md">
    <div class="container mx-auto px-4">
        <div class="flex items-center justify-between py-3">
            <!-- Logo & App Name -->
            <div class="text-white font-bold text-lg">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 transform hover:scale-105 transition-all duration-300 ease-in-out">
                    <img src="{{ asset('storage/' . $setting->logo) }}?v={{ time() }}" class="w-8 h-8 rounded" alt="Logo">
                    <span class="transition-colors duration-300">{{ $setting->app_name ?? config('app.name') }}</span>
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white transition duration-300 hover:scale-110">
                    <i class="fas fa-cube mr-1"></i> Produk
                </a>
                <a href="{{ route('suppliers.index') }}" class="text-gray-300 hover:text-white transition duration-300 hover:scale-110">
                    <i class="fas fa-truck mr-1"></i> Penyuplai
                </a>
                <a href="{{ route('stock_transactions.index') }}" class="text-gray-300 hover:text-white transition duration-300 hover:scale-110">
                    <i class="fas fa-exchange-alt mr-1"></i> Transaksi Stok
                </a>
                <a href="{{ route('stock_opname.index') }}" class="text-gray-300 hover:text-white transition duration-300 hover:scale-110">
                    <i class="fas fa-clipboard-list mr-1"></i> Stok Opname
                </a>

                <!-- Dropdown Laporan -->
                <div class="relative">
                    <button id="laporan-menu-button" class="text-gray-300 hover:text-white transition flex items-center space-x-1 hover:scale-110 duration-300">
                        <i class="fas fa-file-alt"></i>
                        <span>Laporan</span>
                        <i class="fas fa-chevron-down text-gray-300"></i>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div id="laporan-dropdown" class="absolute hidden bg-white shadow-lg rounded-md mt-2 w-48 right-0 z-50 transform scale-95 opacity-0 transition-all duration-300">
                        <a href="{{ route('laporan.stok') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Stok</a>
                        <a href="{{ route('laporan.aktivitas') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Laporan Aktivitas</a>
                    </div>
                </div>

                <a href="{{ route('users.index') }}" class="text-gray-300 hover:text-white transition duration-300 hover:scale-110">
                    <i class="fas fa-users mr-1"></i> Pengguna
                </a>

                <!-- User Dropdown -->
                @auth
                <div class="relative">
                    <button id="user-menu-button" class="flex items-center space-x-2 bg-purple-800 px-3 py-1 rounded-lg hover:bg-purple-700 transition focus:outline-none hover:scale-105 duration-300">
                        <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('img/logofenn.png') }}" alt="user photo">
                        <span class="text-white">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-gray-300"></i>
                    </button>

                    <!-- Dropdown -->
                    <div id="user-dropdown" class="absolute right-0 mt-2 w-56 bg-white shadow-lg rounded-md hidden transform scale-95 opacity-0 transition-all duration-300 origin-top-right">
                        <div class="px-4 py-3 border-b">
                            <span class="block text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</span>
                            <span class="block text-sm text-gray-500 truncate w-full overflow-hidden text-ellipsis">{{ Auth::user()->email }}</span>
                            <span class="block text-xs text-gray-400">Role: {{ ucfirst(app(\App\Services\UserService::class)->getRoleLabel(Auth::user()->role)) }}</span>
                        </div>
                        <ul class="py-2">
                            <li><a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a></li>
                            <li>
                                <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
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

<!-- Script JavaScript untuk Dropdown dengan Animasi -->
<script>
document.addEventListener("DOMContentLoaded", function () {
    const laporanMenuButton = document.getElementById("laporan-menu-button");
    const laporanDropdown = document.getElementById("laporan-dropdown");
    const userMenuButton = document.getElementById("user-menu-button");
    const userDropdown = document.getElementById("user-dropdown");

    function toggleDropdown(button, dropdown) {
        dropdown.classList.toggle("hidden");
        if (!dropdown.classList.contains("hidden")) {
            dropdown.classList.remove("scale-95", "opacity-0");
            dropdown.classList.add("scale-100", "opacity-100");
        } else {
            dropdown.classList.remove("scale-100", "opacity-100");
            dropdown.classList.add("scale-95", "opacity-0");
        }
    }

    laporanMenuButton.addEventListener("click", function (event) {
        event.stopPropagation();
        toggleDropdown(laporanMenuButton, laporanDropdown);
    });

    userMenuButton.addEventListener("click", function (event) {
        event.stopPropagation();
        toggleDropdown(userMenuButton, userDropdown);
    });

    document.addEventListener("click", function (event) {
        if (!laporanMenuButton.contains(event.target) && !laporanDropdown.contains(event.target)) {
            laporanDropdown.classList.add("hidden", "scale-95", "opacity-0");
        }
        if (!userMenuButton.contains(event.target) && !userDropdown.contains(event.target)) {
            userDropdown.classList.add("hidden", "scale-95", "opacity-0");
        }
    });
});
</script>
