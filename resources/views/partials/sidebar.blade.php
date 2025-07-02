<aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white text-purple-800 shadow-lg transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40 dark:bg-gray-800 dark:text-gray-100">
    <div class="p-4 pt-8 h-full flex flex-col">
        {{-- Logo --}}
        <div class="hidden items-center space-x-2 mb-6 lg:flex">
            <img src="{{ asset('storage/' . $setting->logo) }}?v={{ time() }}" class="w-10 h-10 rounded-lg" alt="Logo">
            <span class="font-bold text-xl dark:text-gray-100">{{ $setting->app_name ?? config('app.name') }}</span>
        </div>

        {{-- Close button for mobile --}}
        <button id="sidebar-close-button" class="absolute top-4 right-4 text-purple-600 hover:text-purple-900 lg:hidden dark:text-gray-300 dark:hover:text-white">
            <i class="fas fa-times text-2xl"></i>
        </button>

        {{-- Content --}}
        <div class="flex-grow overflow-y-auto pr-2">
            <ul class="space-y-2 pb-4">
                <li>
                    {{-- Tambahkan kelas 'group' pada elemen <a> --}}
                    <a href="{{ route('dashboard') }}" class="flex items-center p-3 rounded-lg group
                       hover:bg-purple-600 hover:text-white             {{-- Light mode: bg-purple, text-white on hover --}}
                       dark:hover:bg-white dark:hover:text-purple-800  {{-- Dark mode: bg-white, text-purple on hover --}}
                       transition duration-300 text-purple-800 dark:text-gray-200">
                        {{-- Icon mengikuti warna teks link saat di-hover --}}
                        <i class="fas fa-tachometer-alt mr-3 text-lg
                           group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                        <span class="font-medium">Dashboard</span>
                    </a>
                </li>

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('admin.role-requests') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200 relative">
                            <i class="fas fa-user-check mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Permintaan Role</span>
                            @if($pendingRequests > 0)
                                <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                                    {{ $pendingRequests }}
                                </span>
                            @endif
                        </a>
                    </li>
                @endif

                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'warehouse_manager'))
                    <li>
                        <a href="{{ route('products.index') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200">
                            <i class="fas fa-cube mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Produk</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('suppliers.index') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200">
                            <i class="fas fa-truck mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Penyuplai</span>
                        </a>
                    </li>
                @endif

                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'warehouse_manager'))
                    <li>
                        <a href="{{ route('stock_opname.index') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200">
                            <i class="fas fa-clipboard-list mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Stok Opname</span>
                        </a>
                    </li>
                @endif

                @auth
                <li>
                    <a href="{{ route('stock_transactions.index') }}" class="flex items-center p-3 rounded-lg group
                       hover:bg-purple-600 hover:text-white
                       dark:hover:bg-white dark:hover:text-purple-800
                       transition duration-300 text-purple-800 dark:text-gray-200">
                        <i class="fas fa-exchange-alt mr-3 text-lg
                           group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                        <span class="font-medium">Transaksi Stok</span>
                    </a>
                </li>
                @endauth

                @if(auth()->check() && (auth()->user()->role === 'admin' || auth()->user()->role === 'warehouse_manager'))
                    <li class="relative">
                        {{-- Tombol laporan juga menjadi 'group' --}}
                        <button id="laporan-menu-button-sidebar" class="flex items-center justify-between w-full p-3 rounded-lg group
                            hover:bg-purple-600 hover:text-white
                            dark:hover:bg-white dark:hover:text-purple-800
                            transition duration-300 text-purple-800 dark:text-gray-200 focus:outline-none">
                            <span class="flex items-center">
                                {{-- Icon mengikuti warna teks link saat di-hover --}}
                                <i class="fas fa-file-alt mr-3 text-lg
                                   group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                                <span class="font-medium">Laporan</span>
                            </span>
                            <i id="laporan-chevron" class="fas fa-chevron-down text-purple-600 transform transition-transform duration-300 dark:text-gray-300"></i>
                        </button>
                        <div id="laporan-dropdown-sidebar" class="pl-8 mt-1 space-y-2 hidden">
                            {{-- Untuk sub-link laporan, mereka adalah link individual, jadi hover diterapkan langsung --}}
                            <a href="{{ route('laporan.stok.index') }}" class="block px-4 py-2 text-sm rounded-md transition duration-200
                                hover:bg-purple-600 hover:text-white
                                dark:hover:bg-white dark:hover:text-purple-800
                                text-purple-700 dark:text-gray-300">Laporan Stok</a>
                            @if(auth()->user()->role === 'admin')
                            <a href="{{ route('laporan.aktivitas.index') }}" class="block px-4 py-2 text-sm rounded-md transition duration-200
                                hover:bg-purple-600 hover:text-white
                                dark:hover:bg-white dark:hover:text-purple-800
                                text-purple-700 dark:text-gray-300">Laporan Aktivitas</a>
                            @endif
                        </div>
                    </li>
                @endif

                @if(auth()->check() && auth()->user()->role === 'admin')
                    <li>
                        <a href="{{ route('users.index') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200">
                            <i class="fas fa-users mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Pengguna</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('settings.index') }}" class="flex items-center p-3 rounded-lg group
                           hover:bg-purple-600 hover:text-white
                           dark:hover:bg-white dark:hover:text-purple-800
                           transition duration-300 text-purple-800 dark:text-gray-200">
                            <i class="fas fa-cog mr-3 text-lg
                               group-hover:text-white dark:group-hover:text-purple-800"></i> {{-- ICON MENGIKUTI HOVER GROUP --}}
                            <span class="font-medium">Pengaturan</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

        {{-- Logout --}}
        <div class="mt-auto pt-4 border-t border-gray-200 dark:border-gray-600">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full p-3 rounded-lg group
                    text-red-600
                    hover:bg-red-500 hover:text-white
                    dark:text-red-300
                    dark:hover:bg-red-700 dark:hover:text-white
                    transition duration-300">
                    {{-- Icon mengikuti warna teks link saat di-hover (putih di kedua mode) --}}
                    <i class="fas fa-sign-out-alt mr-3 text-lg
                       group-hover:text-white dark:group-hover:text-white"></i> {{-- ICON MENGIKUTI HOVER GROUP (selalu putih) --}}
                    <span class="font-medium">Sign out</span>
                </button>
            </form>
        </div>
    </div>
</aside>

@push('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Sidebar Laporan Dropdown
        const laporanMenuButtonSidebar = document.getElementById("laporan-menu-button-sidebar");
        const laporanDropdownSidebar = document.getElementById("laporan-dropdown-sidebar");
        const laporanChevron = document.getElementById("laporan-chevron");

        laporanMenuButtonSidebar?.addEventListener("click", function (event) {
            event.stopPropagation();
            laporanDropdownSidebar.classList.toggle("hidden");
            laporanChevron.classList.toggle("rotate-180"); // Rotate chevron
        });

        // Close dropdown when clicking outside
        document.addEventListener("click", function (event) {
            if (laporanDropdownSidebar && !laporanDropdownSidebar.classList.contains("hidden") && !laporanMenuButtonSidebar.contains(event.target)) {
                laporanDropdownSidebar.classList.add("hidden");
                laporanChevron.classList.remove("rotate-180");
            }
        });
    });
</script>
@endpush