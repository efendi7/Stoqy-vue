<nav class="fixed top-0 left-0 w-full z-50 bg-gradient-to-r from-[rgb(47,32,95)] to-purple-600 p-4">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <div class="text-white font-bold">
                <a href="{{ url('/') }}" class="relative group text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-boxes mr-1"></i> Stockify
                </a>
            </div>
            <div class="flex items-center space-x-3">
                @auth
                    <a href="{{ route('dashboard') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                        <i class="fas fa-home mr-1"></i> Dashboard
                    </a>
                @endauth
                <a href="{{ route('products.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-cube mr-1"></i> Products
                </a>
                <a href="{{ route('categories.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-th-list mr-1"></i> Categories
                </a>
                <a href="{{ route('suppliers.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-truck mr-1"></i> Suppliers
                </a>
                <a href="{{ route('product_attributes.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-tags mr-1"></i> Product Attributes
                </a>
                <a href="{{ route('stock_transactions.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-exchange-alt mr-1"></i> Stock Transactions
                </a>
                <a href="{{ route('users.index') }}" class="text-gray-300 px-3 py-1 rounded-lg transition-all duration-300 hover:scale-110 hover:bg-gradient-to-r from-purple-500 to-indigo-400 hover:text-white">
                    <i class="fas fa-users mr-1"></i> Users
                </a>

                @auth
                    <div class="relative">
                        <button type="button" class="flex text-sm bg-purple-900 rounded-full focus:ring-4 focus:ring-gray-300 transition-all duration-300 hover:scale-110 hover:shadow-lg active:scale-95 active:shadow-inner" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                            <span class="sr-only">Open user menu</span>
                            <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('img/logofenn.png') }}" alt="user photo">
                        </button>

                        <!-- Dropdown menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-lg dark:bg-gray-700 dark:divide-gray-600 hidden" id="user-dropdown">
                            <div class="px-4 py-3">
                                <span class="block text-sm text-gray-900 dark:text-white">{{ Auth::user()->name }}</span>
                                <span class="block text-sm text-gray-500 truncate dark:text-gray-400">{{ Auth::user()->email }}</span>
                                <span class="block text-xs text-gray-400">Role: {{ ucfirst(Auth::user()->role) }}</span>
                            </div>
                            <ul class="py-2">
                                <li>
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <i class="fas fa-home mr-1"></i> Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <i class="fas fa-cog mr-1"></i> Settings
                                    </a>
                                </li>
                                <li>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                        <i class="fas fa-dollar-sign mr-1"></i> Earnings
                                    </a>
                                </li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">
                                            <i class="fas fa-sign-out-alt mr-1"></i> Sign out
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const userMenuButton = document.getElementById('user-menu-button');
    const userDropdown = document.getElementById('user-dropdown');

    userMenuButton.addEventListener('click', function() {
        userDropdown.classList.toggle('hidden');
    });
});
</script>
