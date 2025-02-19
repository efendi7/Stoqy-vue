<nav class="bg-gray-800 p-4">
    <div class="container mx-auto">
        <div class="flex items-center justify-between">
            <div class="text-white font-bold">
                <a href="{{ url('/') }}">Stockify</a>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('products.index') }}" class="text-gray-300 hover:text-white px-3">Products</a>
                <a href="{{ route('categories.index') }}" class="text-gray-300 hover:text-white px-3">Categories</a>
                <a href="{{ route('suppliers.index') }}" class="text-gray-300 hover:text-white px-3">Suppliers</a>
                <a href="{{ route('product_attributes.index') }}" class="text-gray-300 hover:text-white px-3">Product Attributes</a>
                <a href="{{ route('users.index') }}" class="text-gray-300 hover:text-white px-3">Users</a>
                <button type="button" class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600" id="user-menu-button" aria-expanded="false" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom">
                    <span class="sr-only">Open user menu</span>
                    <img class="w-8 h-8 rounded-full object-cover" src="{{ asset('img/logofenn.png') }}" alt="user photo">
                </button>
                <!-- Dropdown menu -->
                <div class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow-sm dark:bg-gray-700 dark:divide-gray-600" id="user-dropdown">
                    <div class="px-4 py-3">
                        <span class="block text-sm text-gray-900 dark:text-white">Fenn</span>
                        <span class="block text-sm text-gray-500 truncate dark:text-gray-400">fenseven@gmail.com</span>
                    </div>
                    <ul class="py-2" aria-labelledby="user-menu-button">
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Dashboard</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Settings</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Earnings</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white">Sign out</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>
<script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite.js"></script>
