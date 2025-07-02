<nav class="fixed top-0 left-0 lg:left-64 w-full lg:w-[calc(100%-16rem)] z-50 bg-gradient-to-r from-purple-900 to-purple-700 dark:from-gray-800 dark:to-gray-700 shadow-md">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3 h-16">
            {{-- Sidebar Toggle Button (Always visible) --}}
            <div class="flex items-center">
                <button onclick="toggleSidebar()" class="text-white dark:text-gray-200 text-2xl focus:outline-none mr-4 hover:text-gray-200 dark:hover:text-white transition-colors duration-300">
                    <i class="fas fa-bars"></i>
                </button>
                {{-- Logo & Nama Aplikasi (Hidden on desktop when sidebar is visible) --}}
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2 transform hover:scale-105 transition-all duration-300 ease-in-out lg:hidden">
                    <img src="{{ asset('storage/' . $setting->logo) }}?v={{ time() }}" class="w-8 h-8 rounded" alt="Logo">
                    <span class="text-white dark:text-gray-100 font-bold text-lg transition-colors duration-300">{{ $setting->app_name ?? config('app.name') }}</span>
                </a>
            </div>

            {{-- Profile Dropdown and Dark Mode Toggle --}}
            <div class="flex items-center space-x-4 ml-auto">
                {{-- Dark Mode Toggle Button --}}
                <button onclick="toggleTheme()" class="text-gray-300 dark:text-gray-400 hover:text-white dark:hover:text-gray-100 transition duration-300 focus:outline-none text-xl hover:scale-110">
                    <i id="theme-icon" class="fas fa-moon"></i>
                </button>

                @auth
                    <div class="relative">
                        <button id="user-menu-button" class="flex items-center space-x-2 bg-purple-800 dark:bg-gray-600 px-3 py-1 rounded-lg hover:bg-purple-700 dark:hover:bg-gray-500 transition focus:outline-none hover:scale-105 duration-300">
                            <img class="w-8 h-8 rounded-full object-cover"
                            src="{{ Auth::user()->profile_picture ? asset('storage/'.Auth::user()->profile_picture) : asset('img/logofenn.png') }}"
                            alt="user photo">
                            <span class="text-white dark:text-gray-100">{{ Auth::user()->name }}</span>
                            <i class="fas fa-chevron-down text-gray-300 dark:text-gray-400"></i>
                        </button>

                        <div id="user-dropdown" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-700 shadow-lg rounded-md hidden transform scale-95 opacity-0 transition-all duration-300 origin-top-right z-50 border dark:border-gray-600">
                            <div class="px-4 py-3 border-b bg-gray-100 dark:bg-gray-800 dark:text-gray-200 border-gray-200 dark:border-gray-600">
                                <p class="text-sm font-semibold text-gray-800 dark:text-gray-100">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300 break-words truncate max-w-[200px]">
                                    {{ Auth::user()->email }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ ucfirst(app(\App\Services\UserService::class)->getRoleLabel(auth()->user()->role)) }}
                                </p>
                            </div>
                            <ul class="py-2 bg-white dark:bg-gray-700">
                                <li>
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-user mr-2"></i>Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Dashboard
                                    </a>
                                </li>
                                @if(auth()->user()->role === 'admin')
                                <li>
                                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-cog mr-2"></i>Pengaturan
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.role-requests') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors duration-200">
                                        <i class="fas fa-user-check mr-2"></i>Permintaan Role
                                        @if($pendingRequests > 0)
                                            <span class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">{{ $pendingRequests }}</span>
                                        @endif
                                    </a>
                                </li>
                                @endif
                                <li class="border-t border-gray-200 dark:border-gray-600 mt-2 pt-2">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors duration-200">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Sign out
                                        </button>
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

{{-- Overlay for mobile sidebar --}}
<div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden lg:hidden" onclick="closeSidebar()"></div>

<script>
    // Global functions - pastikan ini bisa diakses
    function toggleTheme() {
        console.log('Toggle theme clicked'); // Debug
        const html = document.documentElement;
        const themeIcon = document.getElementById('theme-icon');
        
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            themeIcon.className = 'fas fa-moon';
            localStorage.setItem('theme', 'light');
            console.log('Switched to light mode');
        } else {
            html.classList.add('dark');
            themeIcon.className = 'fas fa-sun';
            localStorage.setItem('theme', 'dark');
            console.log('Switched to dark mode');
        }
    }

    function toggleSidebar() {
        console.log('Toggle sidebar clicked'); // Debug
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (!sidebar) {
            console.error('Sidebar element not found!');
            return;
        }
        
        const isHidden = sidebar.classList.contains('-translate-x-full');
        
        if (isHidden) {
            // Open sidebar
            sidebar.classList.remove('-translate-x-full');
            if (window.innerWidth < 1024) {
                overlay.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
            console.log('Sidebar opened');
        } else {
            // Close sidebar
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
            document.body.style.overflow = '';
            console.log('Sidebar closed');
        }
    }

    function closeSidebar() {
        console.log('Close sidebar called'); // Debug
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        if (sidebar) {
            sidebar.classList.add('-translate-x-full');
        }
        if (overlay) {
            overlay.classList.add('hidden');
        }
        document.body.style.overflow = '';
    }

    function toggleUserDropdown() {
        const dropdown = document.getElementById('user-dropdown');
        if (dropdown) {
            dropdown.classList.toggle('hidden');
            if (!dropdown.classList.contains('hidden')) {
                dropdown.classList.remove('scale-95', 'opacity-0');
                dropdown.classList.add('scale-100', 'opacity-100');
            } else {
                dropdown.classList.add('scale-95', 'opacity-0');
                dropdown.classList.remove('scale-100', 'opacity-100');
            }
        }
    }

    // Initialize theme when page loads
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, initializing theme'); // Debug
        
        // Initialize theme
        const savedTheme = localStorage.getItem('theme') || 'light';
        const themeIcon = document.getElementById('theme-icon');
        
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
            if (themeIcon) themeIcon.className = 'fas fa-sun';
        } else {
            document.documentElement.classList.remove('dark');
            if (themeIcon) themeIcon.className = 'fas fa-moon';
        }
        
        // User dropdown event listener
        const userMenuButton = document.getElementById('user-menu-button');
        if (userMenuButton) {
            userMenuButton.addEventListener('click', function(e) {
                e.stopPropagation();
                toggleUserDropdown();
            });
        }
        
        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('user-dropdown');
            const button = document.getElementById('user-menu-button');
            
            if (dropdown && !dropdown.classList.contains('hidden') && 
                !button.contains(e.target) && !dropdown.contains(e.target)) {
                dropdown.classList.add('hidden', 'scale-95', 'opacity-0');
                dropdown.classList.remove('scale-100', 'opacity-100');
            }
        });
        
        // Close sidebar with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeSidebar();
            }
        });
        
        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 1024) {
                const overlay = document.getElementById('sidebar-overlay');
                if (overlay) overlay.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });
        
        console.log('All event listeners initialized');
    });
</script>

@push('scripts')
<script>
    // Backup script jika yang di atas tidak bekerja
    console.log('Navbar script loaded');
</script>
@endpush