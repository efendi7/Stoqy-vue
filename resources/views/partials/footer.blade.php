{{-- resources/views/layouts/partials/footer.blade.php --}}

<footer class="bg-gray-800 border-t border-gray-700">
    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-center text-center sm:text-left">
            {{-- Copyright Text --}}
            <div class="text-sm text-gray-400">
                &copy; {{ date('Y') }} <a href="#" class="hover:text-white font-semibold">{{ $setting->app_name ?? config('app.name') }}</a>. All Rights Reserved.
            </div>

            {{-- Social Media Links (Contoh) --}}
            <div class="flex space-x-6 mt-4 sm:mt-0">
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i class="fab fa-facebook-f"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i class="fab fa-instagram"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-white transition-colors duration-300">
                    <i class="fab fa-twitter"></i>
                </a>
            </div>
        </div>
    </div>
</footer>