<aside id="sidebar"
    class="fixed top-0 left-0 h-full w-64 bg-[#161B22]/80 text-gray-300 backdrop-blur-lg border-r border-white/10
           transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out z-40">

    <div class="p-4 pt-6 h-full flex flex-col">
        {{-- Logo & Nama Aplikasi --}}
        <div class="flex items-center space-x-3 mb-8 px-2">
            <img src="{{ asset('storage/' . $setting->logo) }}?v={{ time() }}"
                class="w-10 h-10 rounded-lg shadow-md" alt="Logo">
            <span class="font-mova font-bold text-xl text-white">{{ $setting->app_name ?? config('app.name') }}</span>
        </div>

        {{-- Tombol Tutup (Mobile) --}}
        <button id="sidebar-close-button" class="absolute top-4 right-4 text-gray-400 hover:text-white lg:hidden">
            <i class="fas fa-times text-2xl"></i>
        </button>

        {{-- Konten Menu dengan Scroll --}}
        <div class="flex-grow overflow-y-auto pr-2 -mr-2 custom-scrollbar">
            <ul class="space-y-1 pb-4">
                {{-- Bagian Utama --}}
                <li class="px-3 pt-4 pb-2">
                    <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Utama</span>
                </li>
                <x-sidebar.sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                    icon="fa-tachometer-alt">Dashboard</x-sidebar.sidebar-link>

                @if (auth()->check() && auth()->user()->role === 'admin')
                    <x-sidebar.sidebar-link :href="route('admin.role-requests')" :active="request()->routeIs('admin.role-requests')" icon="fa-user-check"
                        :badge="$pendingRequests">Permintaan Role</x-sidebar.sidebar-link>
                @endif

                {{-- Bagian Manajemen --}}
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'warehouse_manager']))
                    <li class="px-3 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Manajemen</span>
                    </li>
                    <li>
                        <button id="produk-menu-button"
                            class="flex items-center justify-between w-full p-3 rounded-lg group text-gray-300 hover:bg-white/5 transition duration-200 focus:outline-none">
                            <span class="flex items-center">
                                <i
                                    class="fas fa-cube w-6 text-center mr-3 text-lg text-gray-400 group-hover:text-white"></i>
                                <span class="font-medium">Produk</span>
                            </span>
                            <i id="produk-chevron"
                                class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-300 group-hover:text-white {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('product_attributes.*') ? 'rotate-180' : '' }}"></i>
                        </button>
                        <div id="produk-dropdown"
                            class="pl-8 mt-1 space-y-1 {{ request()->routeIs('products.*') || request()->routeIs('categories.*') || request()->routeIs('product_attributes.*') ? '' : 'hidden' }}">
                            <a href="{{ route('products.index') }}"
                                class="block p-2 text-sm rounded-md transition duration-200 {{ request()->routeIs('products.index') ? 'text-white bg-white/10' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">Daftar
                                Produk</a>
                            <a href="{{ route('categories.index') }}"
                                class="block p-2 text-sm rounded-md transition duration-200 {{ request()->routeIs('categories.*') ? 'text-white bg-white/10' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">Kategori
                                Produk</a>
                            <a href="{{ route('product_attributes.index') }}"
                                class="block p-2 text-sm rounded-md transition duration-200 {{ request()->routeIs('product_attributes.*') ? 'text-white bg-white/10' : 'text-gray-400 hover:bg-white/5 hover:text-white' }}">Atribut
                                Produk</a>
                        </div>
                    </li>
                    <x-sidebar.sidebar-link :href="route('suppliers.index')" :active="request()->routeIs('suppliers.*')"
                        icon="fa-truck">Penyuplai</x-sidebar.sidebar-link>
                    <x-sidebar.sidebar-link :href="route('stock_opname.index')" :active="request()->routeIs('stock_opname.*')" icon="fa-clipboard-list">Stok
                        Opname</x-sidebar.sidebar-link>
                @endif

                @auth
                    <x-sidebar.sidebar-link :href="route('stock_transactions.index')" :active="request()->routeIs('stock_transactions.*')" icon="fa-exchange-alt">Transaksi
                        Stok</x-sidebar.sidebar-link>
                @endauth

                {{-- Laporan Dropdown --}}
                @if (auth()->check() && in_array(auth()->user()->role, ['admin', 'warehouse_manager']))
                    <li>
                        <button id="laporan-menu-button-sidebar"
                            class="flex items-center justify-between w-full p-3 rounded-lg group text-gray-300 hover:bg-white/5 transition duration-200 focus:outline-none">
                            <span class="flex items-center">
                                <i
                                    class="fas fa-file-alt w-6 text-center mr-3 text-lg text-gray-400 group-hover:text-white"></i>
                                <span class="font-medium">Laporan</span>
                            </span>
                            <i id="laporan-chevron"
                                class="fas fa-chevron-down text-xs text-gray-400 transform transition-transform duration-300 group-hover:text-white"></i>
                        </button>
                        <div id="laporan-dropdown-sidebar" class="pl-8 mt-1 space-y-1 hidden">
                            <a href="{{ route('laporan.stok.index') }}"
                                class="block p-2 text-sm rounded-md transition duration-200 text-gray-400 hover:bg-white/5 hover:text-white">Laporan
                                Stok</a>
                            @if (auth()->user()->role === 'admin')
                                <a href="{{ route('laporan.aktivitas.index') }}"
                                    class="block p-2 text-sm rounded-md transition duration-200 text-gray-400 hover:bg-white/5 hover:text-white">Laporan
                                    Aktivitas</a>
                            @endif
                        </div>
                    </li>
                @endif

                {{-- Bagian Sistem --}}
                @if (auth()->check() && auth()->user()->role === 'admin')
                    <li class="px-3 pt-4 pb-2">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Sistem</span>
                    </li>
                    <x-sidebar.sidebar-link :href="route('users.index')" :active="request()->routeIs('users.*')"
                        icon="fa-users">Pengguna</x-sidebar.sidebar-link>
                    <x-sidebar.sidebar-link :href="route('settings.index')" :active="request()->routeIs('settings.*')"
                        icon="fa-cog">Pengaturan</x-sidebar.sidebar-link>
                @endif
            </ul>
        </div>

        {{-- Tombol Logout --}}
        <div class="mt-auto pt-4 border-t border-white/10">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                    class="flex items-center w-full p-3 rounded-lg group text-red-400 hover:bg-red-500/20 hover:text-red-300 transition duration-200">
                    <i class="fas fa-sign-out-alt w-6 text-center mr-3 text-lg"></i>
                    <span class="font-medium">Sign out</span>
                </button>
            </form>
        </div>
    </div>
</aside>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Definisikan semua komponen dropdown di sini
    const dropdowns = [
        {
            button: document.getElementById("produk-menu-button"),
            dropdown: document.getElementById("produk-dropdown"),
            chevron: document.getElementById("produk-chevron")
        },
        {
            button: document.getElementById("laporan-menu-button-sidebar"),
            dropdown: document.getElementById("laporan-dropdown-sidebar"),
            chevron: document.getElementById("laporan-chevron")
        }
    ];

    // Fungsi untuk menutup semua dropdown
    function closeAllDropdowns(exceptButton = null) {
        dropdowns.forEach(item => {
            if (item.button !== exceptButton && item.dropdown && !item.dropdown.classList.contains('hidden')) {
                item.dropdown.classList.add('hidden');
                item.chevron?.classList.remove('rotate-180');
            }
        });
    }

    // Tambahkan event listener ke setiap tombol dropdown
    dropdowns.forEach(item => {
        if (item.button) {
            item.button.addEventListener("click", function(event) {
                event.stopPropagation(); // Mencegah event lain terpicu
                
                // Simpan status dropdown saat ini (apakah sedang terbuka?)
                const isCurrentlyOpen = !item.dropdown.classList.contains('hidden');

                // Selalu tutup dropdown lain terlebih dahulu
                closeAllDropdowns();

                // Buka kembali dropdown ini jika tadinya tertutup
                if (!isCurrentlyOpen) {
                    item.dropdown.classList.remove('hidden');
                    item.chevron?.classList.add('rotate-180');
                }
            });
        }
    });

    // Event listener untuk menutup dropdown HANYA jika klik di luar komponen
    document.addEventListener("click", function(event) {
        let clickedInsideAComponent = false;

        // Periksa apakah target klik berada di dalam tombol ATAU menu dropdown
        for (const item of dropdowns) {
            const isInsideButton = item.button?.contains(event.target);
            const isInsideDropdown = item.dropdown?.contains(event.target);

            if (isInsideButton || isInsideDropdown) {
                clickedInsideAComponent = true;
                break; // Jika ya, hentikan pengecekan
            }
        }

        // Jika klik terjadi di luar semua komponen dropdown, tutup semuanya.
        if (!clickedInsideAComponent) {
            closeAllDropdowns();
        }
    });
});
</script>
@endpush