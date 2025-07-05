{{-- resources/views/partials/dashboard-period-filter.blade.php --}}

<div
    class="grid grid-cols-1 md:grid-cols-2 gap-8 backdrop-blur-xl bg-gradient-to-r from-indigo-50/80 to-purple-50/80 border border-indigo-200/30 p-8 rounded-2xl shadow-lg mb-8 animate-fade-in-up delay-100 dark:from-gray-800/20 dark:to-gray-900/20 dark:border-gray-700/20 dark:shadow-2xl">
    
    {{-- Informasi Periode --}}
    <div>
        <h3 class="text-xl font-semibold text-indigo-700 flex items-center mb-4 dark:text-indigo-300">
            <span class="w-3 h-3 bg-indigo-500 rounded-full mr-3 animate-ping"></span>Informasi Periode
        </h3>
        <p
            class="text-2xl font-bold mb-4 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent dark:from-indigo-200 dark:to-purple-200">
            {{ isset($startDate) ? $startDate->format('d M Y') : now()->subDays(30)->format('d M Y') }}
            - {{ isset($endDate) ? $endDate->format('d M Y') : now()->format('d M Y') }}
        </p>
        <div class="flex flex-wrap gap-6">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700 dark:text-gray-200">Transaksi Masuk:</span>
                <span class="font-bold text-green-600 text-lg dark:text-green-400">{{ $metrics['incomingTransactions']['value'] ?? ($incomingTransactions ?? 0) }}</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div>
                <span class="text-gray-700 dark:text-gray-200">Transaksi Keluar:</span>
                <span class="font-bold text-red-600 text-lg dark:text-red-400">{{ $metrics['outgoingTransactions']['value'] ?? ($outgoingTransactions ?? 0) }}</span>
            </div>
        </div>
    </div>

   
  {{-- Filter Periode --}}
<div class="mt-8 md:mt-0">
    <h3 class="text-gray-800 text-xl font-semibold mb-6 flex items-center dark:text-gray-100">
        <span class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></span>Filter Periode
    </h3>
    <div class="flex flex-wrap items-end gap-6">
        {{-- Input Tanggal Mulai --}}
        <div class="flex flex-col group">
            <label for="start_date"
                class="mb-2 text-sm text-gray-600 group-focus-within:text-blue-600 transition-colors duration-300 dark:text-gray-300 dark:group-focus-within:text-blue-300">Dari:</label>
            <input type="date" id="start_date" name="start_date"
                value="{{ isset($startDate) ? $startDate->format('Y-m-d') : now()->subDays(30)->format('Y-m-d') }}"
                class="bg-white/80 backdrop-blur-sm border border-gray-300 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 transition-all duration-300 hover:bg-white dark:bg-white/5 dark:border-white/20 dark:text-gray-200 dark:focus:ring-blue-500/50 dark:focus:border-blue-400/50 dark:hover:bg-white/10">
        </div>

        {{-- Input Tanggal Selesai --}}
        <div class="flex flex-col group">
            <label for="end_date"
                class="mb-2 text-sm text-gray-600 group-focus-within:text-blue-600 transition-colors duration-300 dark:text-gray-300 dark:group-focus-within:text-blue-300">Sampai:</label>
            <input type="date" id="end_date" name="end_date"
                value="{{ isset($endDate) ? $endDate->format('Y-m-d') : now()->format('Y-m-d') }}"
                class="bg-white/80 backdrop-blur-sm border border-gray-300 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 transition-all duration-300 hover:bg-white dark:bg-white/5 dark:border-white/20 dark:text-gray-200 dark:focus:ring-blue-500/50 dark:focus:border-blue-400/50 dark:hover:bg-white/10">
        </div>

        {{-- Tombol Terapkan dengan Animasi Baru --}}
        <button type="submit"
            class="group relative h-[52px] w-40 flex justify-center items-center bg-gray-200 dark:bg-gray-700 rounded-xl font-medium text-gray-800 dark:text-gray-200 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 overflow-hidden">
            
            {{-- ELEMEN YANG DIUBAH --}}
            <div
                class="absolute left-0 top-0 h-full w-full rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 transition-transform duration-500 ease-in-out
                       group-hover:scale-x-[.325] group-hover:translate-x-[54px]">
                {{-- Penjelasan:
                    - w-40 = 160px. Ukuran akhir 52px. Skala = 52/160 = 0.325
                    - Pergeseran (translate) = (160/2) - (52/2) = 80 - 26 = 54px
                    - Kita menggunakan nilai arbitrary Tailwind: scale-x-[.325] dan translate-x-[54px]
                --}}
            </div>

            <div class="relative z-10 w-full h-full flex items-center justify-center">
                <span
                    class="transition-all duration-300 text-white 
                           group-hover:text-gray-800 dark:group-hover:text-gray-200 group-hover:-translate-x-4">
                    Terapkan
                </span>

                <svg class="w-5 h-5 ml-2 transition-all duration-500 ease-in-out text-white absolute right-4 
                            opacity-0 group-hover:opacity-100"
                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                    </path>
                </svg>
            </div>
        </button>
    </div>
</div>
</div>