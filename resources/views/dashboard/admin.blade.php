@extends('layouts.app')

@section('content')
    {{-- Enhanced Dashboard with Modern Animations and Glassmorphism --}}
    <div
        class="min-h-screen bg-white text-gray-900 p-4 relative overflow-hidden
               dark:bg-gradient-to-br dark:from-[#0A0E15] dark:via-[#0F1419] dark:to-[#1A1F2E] dark:text-gray-100">

        {{-- Animated Background Elements --}}
        <div class="fixed inset-0 overflow-hidden pointer-events-none">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl animate-pulse dark:bg-blue-600/10"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-1000 dark:bg-purple-600/10"></div>
            <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/3 rounded-full blur-3xl animate-spin-slow dark:bg-indigo-600/5"></div>
        </div>

        <div class="container mx-auto px-4 mt-8 relative z-10">
            {{-- Welcome Banner, Filter, and Metric Cards --}}
            {{-- ... Kode ini tidak perlu diubah ... --}}
            <div class="backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg mb-8 transform hover:scale-[1.02] transition-all duration-500 group animate-fade-in-up dark:bg-purple-900/10 dark:border-purple-800/20 dark:shadow-2xl">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-500/5 to-purple-500/5 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 dark:from-blue-600/10 dark:to-purple-600/10"></div>
                <div class="relative z-10">
                    <h2 class="text-3xl md:text-4xl font-bold bg-gradient-to-r from-blue-600 via-purple-600 to-indigo-600 bg-clip-text text-transparent animate-gradient-x dark:from-blue-400 dark:via-purple-400 dark:to-indigo-400">
                        Welcome <span class="capitalize">{{ ucfirst(app(\App\Services\UserService::class)->getRoleLabel(auth()->user()->role)) }}</span>, {{ auth()->user()->name }}!
                    </h2>
                    <p class="text-blue-700/80 mt-3 text-lg animate-fade-in delay-300 dark:text-blue-300/80">Selamat datang di dashboard Anda. Mari kelola inventaris Anda dengan mudah.</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 backdrop-blur-xl bg-gradient-to-r from-indigo-50/80 to-purple-50/80 border border-indigo-200/30 p-8 rounded-2xl shadow-lg mb-8 animate-fade-in-up delay-100 dark:from-gray-800/20 dark:to-gray-900/20 dark:border-gray-700/20 dark:shadow-2xl">
                <div>
                    <h3 class="text-xl font-semibold text-indigo-700 flex items-center mb-4 dark:text-indigo-300"><span class="w-3 h-3 bg-indigo-500 rounded-full mr-3 animate-ping"></span>Informasi Periode</h3>
                    <p class="text-2xl font-bold mb-4 bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent dark:from-indigo-200 dark:to-purple-200">{{ date('d M Y', strtotime($startDate ?? '-30 days')) }} - {{ date('d M Y', strtotime($endDate ?? 'now')) }}</p>
                    <div class="flex flex-wrap gap-6">
                        <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div><span class="text-gray-700 dark:text-gray-200">Transaksi Masuk:</span><span class="font-bold text-green-600 text-lg dark:text-green-400">{{ $incomingTransactions }}</span></div>
                        <div class="flex items-center space-x-2"><div class="w-3 h-3 bg-red-500 rounded-full animate-pulse"></div><span class="text-gray-700 dark:text-gray-200">Transaksi Keluar:</span><span class="font-bold text-red-600 text-lg dark:text-red-400">{{ $outgoingTransactions }}</span></div>
                    </div>
                </div>
                <div class="mt-8 md:mt-0">
                    <h3 class="text-gray-800 text-xl font-semibold mb-6 flex items-center dark:text-gray-100"><span class="w-2 h-2 bg-blue-500 rounded-full mr-3 animate-pulse"></span>Filter Periode</h3>
                    <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap items-end gap-6">
                        <div class="flex flex-col group"><label for="start_date" class="mb-2 text-sm text-gray-600 group-focus-within:text-blue-600 transition-colors duration-300 dark:text-gray-300 dark:group-focus-within:text-blue-300">Dari:</label><input type="date" id="start_date" name="start_date" value="{{ \Carbon\Carbon::parse($startDate ?? '-30 days')->format('Y-m-d') }}" class="bg-white/80 backdrop-blur-sm border border-gray-300 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 transition-all duration-300 hover:bg-white dark:bg-white/5 dark:border-white/20 dark:text-gray-200 dark:focus:ring-blue-500/50 dark:focus:border-blue-400/50 dark:hover:bg-white/10"></div>
                        <div class="flex flex-col group"><label for="end_date" class="mb-2 text-sm text-gray-600 group-focus-within:text-blue-600 transition-colors duration-300 dark:text-gray-300 dark:group-focus-within:text-blue-300">Sampai:</label><input type="date" id="end_date" name="end_date" value="{{ \Carbon\Carbon::parse($endDate ?? 'now')->format('Y-m-d') }}" class="bg-white/80 backdrop-blur-sm border border-gray-300 rounded-xl px-4 py-3 text-gray-800 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-400/50 transition-all duration-300 hover:bg-white dark:bg-white/5 dark:border-white/20 dark:text-gray-200 dark:focus:ring-blue-500/50 dark:focus:border-blue-400/50 dark:hover:bg-white/10"></div>
                        <button type="submit" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-3 rounded-xl font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:scale-105 hover:shadow-lg hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500/50 dark:from-blue-700 dark:to-indigo-700 dark:hover:from-blue-800 dark:hover:to-indigo-800 dark:hover:shadow-blue-600/25 dark:focus:ring-blue-600/50"><span class="flex items-center">Terapkan<svg class="w-4 h-4 ml-2 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg></span></button>
                    </form>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                @php
                    $metrics = [
                        ['label' => 'Total Produk', 'value' => $totalProducts, 'gradient' => 'from-green-100 to-emerald-100', 'border' => 'border-green-200', 'text' => 'text-green-700', 'icon' => '📦', 'delay' => '0', 'dark_gradient' => 'dark:from-green-500/20 dark:to-emerald-500/20', 'dark_border' => 'dark:border-green-400/30', 'dark_text' => 'dark:text-green-300'],
                        ['label' => 'Stok Tersedia', 'value' => $availableStock, 'gradient' => 'from-blue-100 to-cyan-100', 'border' => 'border-blue-200', 'text' => 'text-blue-700', 'icon' => '✅', 'delay' => '100', 'dark_gradient' => 'dark:from-blue-500/20 dark:to-cyan-500/20', 'dark_border' => 'dark:border-blue-400/30', 'dark_text' => 'dark:text-blue-300'],
                        ['label' => 'Stok Rendah', 'value' => $lowStockItems, 'gradient' => 'from-yellow-100 to-orange-100', 'border' => 'border-yellow-200', 'text' => 'text-yellow-700', 'icon' => '⚠️', 'delay' => '200', 'dark_gradient' => 'dark:from-yellow-500/20 dark:to-orange-500/20', 'dark_border' => 'dark:border-yellow-400/30', 'dark_text' => 'dark:text-yellow-300'],
                        ['label' => 'Stok Habis', 'value' => $outOfStock, 'gradient' => 'from-red-100 to-pink-100', 'border' => 'border-red-200', 'text' => 'text-red-700', 'icon' => '❌', 'delay' => '300', 'dark_gradient' => 'dark:from-red-500/20 dark:to-pink-500/20', 'dark_border' => 'dark:border-red-400/30', 'dark_text' => 'dark:text-red-300'],
                        ['label' => 'Transaksi Masuk', 'value' => $incomingTransactions ?? 0, 'gradient' => 'from-indigo-100 to-purple-100', 'border' => 'border-indigo-200', 'text' => 'text-indigo-700', 'icon' => '📥', 'delay' => '400', 'dark_gradient' => 'dark:from-indigo-500/20 dark:to-purple-500/20', 'dark_border' => 'dark:border-indigo-400/30', 'dark_text' => 'dark:text-indigo-300'],
                        ['label' => 'Transaksi Keluar', 'value' => $outgoingTransactions ?? 0, 'gradient' => 'from-purple-100 to-pink-100', 'border' => 'border-purple-200', 'text' => 'text-purple-700', 'icon' => '📤', 'delay' => '500', 'dark_gradient' => 'dark:from-purple-500/20 dark:to-pink-500/20', 'dark_border' => 'dark:border-purple-400/30', 'dark_text' => 'dark:text-purple-300'],
                        ['label' => 'Total Users', 'value' => $totalUsers, 'gradient' => 'from-teal-100 to-cyan-100', 'border' => 'border-teal-200', 'text' => 'text-teal-700', 'icon' => '👥', 'delay' => '600', 'dark_gradient' => 'dark:from-teal-500/20 dark:to-cyan-500/20', 'dark_border' => 'dark:border-teal-400/30', 'dark_text' => 'dark:text-teal-300'],
                        ['label' => 'Total Suppliers', 'value' => $totalSuppliers, 'gradient' => 'from-emerald-100 to-green-100', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'icon' => '🏭', 'delay' => '700', 'dark_gradient' => 'dark:from-emerald-500/20 dark:to-green-500/20', 'dark_border' => 'dark:border-emerald-400/30', 'dark_text' => 'dark:text-emerald-300'],
                    ];
                @endphp
                @foreach ($metrics as $metric)
                    <x-dashboard.metric-card :label="$metric['label']" :value="$metric['value']" :gradient="$metric['gradient']" :border="$metric['border']" :text="$metric['text']" :icon="$metric['icon']" :delay="$metric['delay']" :dark_gradient="$metric['dark_gradient']" :dark_border="$metric['dark_border']" :dark_text="$metric['dark_text']" />
                @endforeach
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                <div class="backdrop-blur-xl bg-white/80 border border-gray-200/50 p-6 md:p-8 rounded-2xl shadow-lg animate-fade-in-up dark:bg-white/5 dark:border-white/10" style="animation-delay: 0ms;">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center dark:text-gray-100"><span class="text-2xl mr-3">📊</span>Stok Barang</h3>
                    <div class="h-80"><canvas id="stockChart"></canvas></div>
                </div>
                <div class="backdrop-blur-xl bg-white/80 border border-gray-200/50 p-6 md:p-8 rounded-2xl shadow-lg animate-fade-in-up dark:bg-white/5 dark:border-white/10" style="animation-delay: 100ms;">
                    <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center dark:text-gray-100"><span class="text-2xl mr-3">📈</span>Laporan Transaksi</h3>
                    <div class="h-80"><canvas id="transactionReportChart"></canvas></div>
                </div>
            </div>
            
            <div id="activity-section" class="backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg animate-fade-in-up delay-500 dark:bg-white/5 dark:border-white/10 dark:shadow-2xl">
                <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center dark:text-gray-100"><span class="text-2xl mr-3">🔥</span>Aktivitas Hari Ini<div class="ml-auto w-3 h-3 bg-green-500 rounded-full animate-pulse"></div></h3>
                @if ($recentActivities->isNotEmpty())
                    <div class="backdrop-blur-sm bg-white/80 border border-gray-200/50 rounded-xl overflow-hidden dark:bg-white/5 dark:border-white/10">
                        <div class="bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 font-medium px-6 py-4 flex text-sm border-b border-gray-200 dark:from-gray-800/50 dark:to-gray-700/50 dark:text-gray-200 dark:border-white/10"><span class="w-2/5 flex items-center"><span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>Aksi</span><span class="w-1/4 text-center text-gray-700 dark:text-gray-100">Waktu</span><span class="w-1/3 text-right pr-3 text-gray-700 dark:text-gray-100">Pengguna</span></div>
                        <div class="divide-y divide-gray-200 dark:divide-white/10">
                            @foreach ($recentActivities as $index => $activity)
                                <div class="px-6 py-4 flex items-center text-sm hover:bg-gray-100/50 transition-all duration-300 animate-fade-in-up dark:hover:bg-gray-700/15" style="animation-delay: {{ $index * 50 }}ms">
                                    <div class="w-2/5 truncate"><span class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 border border-blue-200 text-blue-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 dark:from-blue-500/20 dark:to-indigo-500/20 dark:border-blue-400/30 dark:text-blue-300">{{ $activity->action ?? 'Tidak ada aksi' }}</span></div>
                                    <div class="w-1/4 text-center text-gray-600 truncate dark:text-gray-300">{{ $activity->created_at->diffForHumans() }}</div>
                                    <div class="w-1/3 flex justify-end"><span class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-gray-100 to-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 dark:from-gray-600/50 dark:to-gray-500/50 dark:border-gray-400/30 dark:text-gray-200">{{ $activity->user->name }}</span></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-8 flex justify-center">{{ $recentActivities->appends(request()->query())->links('vendor.pagination.custom') }}</div>
                @else
                    <div class="text-center py-12"><div class="text-6xl mb-4 opacity-50">📭</div><p class="text-gray-600 text-lg dark:text-gray-300">Tidak ada aktivitas untuk hari ini.</p></div>
                @endif
            </div>

        </div>
    </div>

    @push('styles')
        <style>
            /* Custom scrollbar for light mode */
            html:not(.dark) ::-webkit-scrollbar {
                width: 8px;
            }

            html:not(.dark) ::-webkit-scrollbar-track {
                background: rgba(0, 0, 0, 0.05);
                border-radius: 4px;
            }

            html:not(.dark) ::-webkit-scrollbar-thumb {
                background: rgba(0, 0, 0, 0.2);
                border-radius: 4px;
            }

            html:not(.dark) ::-webkit-scrollbar-thumb:hover {
                background: rgba(0, 0, 0, 0.3);
            }

            /* Dark mode specific scrollbar */
            html.dark ::-webkit-scrollbar {
                width: 8px;
            }

            html.dark ::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.1);
                border-radius: 4px;
            }

            html.dark ::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.3);
                border-radius: 4px;
            }

            html.dark ::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.5);
            }

            @keyframes gradient-x {

                0%,
                100% {
                    background-size: 200% 200%;
                    background-position: left center;
                }

                50% {
                    background-size: 200% 200%;
                    background-position: right center;
                }
            }

            @keyframes fade-in-up {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            @keyframes spin-slow {
                from {
                    transform: rotate(0deg);
                }

                to {
                    transform: rotate(360deg);
                }
            }

            .animate-gradient-x {
                animation: gradient-x 3s ease infinite;
            }

            .animate-fade-in-up {
                animation: fade-in-up 1s ease-out forwards;
                opacity: 0;
            }

            .animate-spin-slow {
                animation: spin-slow 20s linear infinite;
            }

            .delay-100 {
                animation-delay: 100ms;
            }

            .delay-200 {
                animation-delay: 200ms;
            }

            .delay-300 {
                animation-delay: 300ms;
            }

            .delay-400 {
                animation-delay: 400ms;
            }

            .delay-500 {
                animation-delay: 500ms;
            }

            .delay-600 {
                animation-delay: 600ms;
            }

            .delay-700 {
                animation-delay: 700ms;
            }

            .delay-800 {
                animation-delay: 800ms;
            }

            /* Glassmorphism effect enhancement */
            .backdrop-blur-xl {
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            let stockChartInstance = null;
            let transactionReportChartInstance = null;

            function initializeCharts() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                const chartTextColor = isDarkMode ? '#E5E7EB' : '#374151';
                const chartBorderColor = isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

                Chart.defaults.color = chartTextColor;
                Chart.defaults.borderColor = chartBorderColor;
                Chart.defaults.font.family = "'Inter', 'system-ui', 'sans-serif'";

                const commonChartOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                color: chartTextColor,
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12, weight: '500' }
                            },
                            onClick: (e, legendItem, legend) => {
                                const index = legendItem.datasetIndex;
                                const ci = legend.chart;
                                if (ci.isDatasetVisible(index)) {
                                    ci.hide(index);
                                    legendItem.hidden = true;
                                } else {
                                    ci.show(index);
                                    legendItem.hidden = false;
                                }
                            }
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: isDarkMode ? 'rgba(17, 24, 39, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                            titleColor: isDarkMode ? '#F3F4F6' : '#1F2937',
                            bodyColor: isDarkMode ? '#E5E7EB' : '#4B5563',
                            borderColor: isDarkMode ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)',
                            borderWidth: 1,
                            cornerRadius: 12,
                            padding: 12,
                            displayColors: true,
                            usePointStyle: true,
                        }
                    },
                    scales: {
                        x: {
                            grid: { drawBorder: false },
                            ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                        },
                        y: {
                            grid: { drawBorder: false },
                            ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                        }
                    },
                    elements: {
                        point: { radius: 3, hoverRadius: 6, borderWidth: 2 },
                        line: { tension: 0.4 }
                    },
                    animation: { duration: 1500, easing: 'easeInOutQuart' }
                };

                if (stockChartInstance) stockChartInstance.destroy();
                if (transactionReportChartInstance) transactionReportChartInstance.destroy();

                // --- 1. Stok Chart (DIUBAH MENJADI HORIZONTAL) ---
                const stockCtx = document.getElementById('stockChart')?.getContext('2d');
                if (stockCtx) {
                    stockChartInstance = new Chart(stockCtx, {
                        type: 'bar', // Tipe tetap 'bar'
                        data: {
                            labels: {!! json_encode($stockLabels ?? []) !!},
                            datasets: [{
                                label: 'Stok Barang',
                                data: {!! json_encode($stockData ?? []) !!},
                                backgroundColor: (ctx) => {
                                    const gradient = ctx.chart.ctx.createLinearGradient(0, 0, 600, 0); // Gradien horizontal
                                    gradient.addColorStop(0, isDarkMode ? 'rgba(34, 197, 94, 0.2)' : 'rgba(22, 163, 74, 0.2)');
                                    gradient.addColorStop(1, isDarkMode ? 'rgba(34, 197, 94, 0.9)' : 'rgba(22, 163, 74, 0.9)');
                                    return gradient;
                                },
                                borderColor: isDarkMode ? '#22C55E' : '#16A34A',
                                borderWidth: 2,
                                // Atur radius di ujung bar horizontal
                                borderRadius: { topRight: 6, bottomRight: 6, topLeft: 0, bottomLeft: 0 },
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            ...commonChartOptions,
                            indexAxis: 'y', // <-- KUNCI UTAMA: Membuat chart menjadi horizontal
                            scales: {
                                // Sumbu X (sekarang untuk nilai/angka)
                                x: {
                                    beginAtZero: true,
                                    grid: { drawBorder: false },
                                    ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                                },
                                // Sumbu Y (sekarang untuk label produk)
                                y: {
                                    grid: { drawBorder: false },
                                    ticks: { color: isDarkMode ? '#9CA3AF' : '#6B7280' }
                                }
                            },
                            plugins: {
                                ...commonChartOptions.plugins,
                                legend: {
                                    display: false // Legenda tidak terlalu berguna untuk 1 data
                                }
                            }
                        }
                    });
                }

                // --- 2. Transaction Report Chart (Tidak ada perubahan) ---
                const transactionCtx = document.getElementById('transactionReportChart')?.getContext('2d');
                if (transactionCtx) {
                    transactionReportChartInstance = new Chart(transactionCtx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($transactionLabels ?? []) !!},
                            datasets: [
                                {
                                    label: 'Transaksi Masuk',
                                    data: {!! json_encode($incomingTransactionData ?? []) !!},
                                    borderColor: isDarkMode ? '#3B82F6' : '#2563EB',
                                    backgroundColor: isDarkMode ? 'rgba(59, 130, 246, 0.2)' : 'rgba(37, 99, 235, 0.2)',
                                    fill: true,
                                }, {
                                    label: 'Transaksi Keluar',
                                    data: {!! json_encode($outgoingTransactionData ?? []) !!},
                                    borderColor: isDarkMode ? '#EF4444' : '#DC2626',
                                    backgroundColor: isDarkMode ? 'rgba(239, 68, 68, 0.2)' : 'rgba(220, 38, 38, 0.2)',
                                    fill: true,
                                }, {
                                    label: 'Total Transaksi',
                                    data: {!! json_encode($combinedTransactionData ?? []) !!},
                                    borderColor: isDarkMode ? '#A855F7' : '#9333EA',
                                    borderWidth: 4,
                                    borderDash: [5, 5],
                                    fill: false,
                                }
                            ]
                        },
                        options: commonChartOptions
                    });
                }
            }

            document.addEventListener('DOMContentLoaded', initializeCharts);
            document.getElementById('theme-toggle')?.addEventListener('click', () => {
                setTimeout(initializeCharts, 100);
            });

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.style.animationPlayState = 'running'; });
            }, { threshold: 0.1, rootMargin: '0px 0px -10% 0px' });
            document.querySelectorAll('.animate-fade-in-up').forEach(el => {
                el.style.animationPlayState = 'paused';
                observer.observe(el);
            });
            function createParticle() {
                const particle = document.createElement('div');
                const isDarkMode = document.documentElement.classList.contains('dark');
                const particleColor = isDarkMode ? 'bg-blue-400/20' : 'bg-blue-600/20';
                particle.className = `fixed w-1 h-1 ${particleColor} rounded-full pointer-events-none animate-ping`;
                particle.style.left = `${Math.random() * 100}vw`;
                particle.style.top = `${Math.random() * 100}vh`;
                particle.style.animationDelay = `${Math.random() * 2}s`;
                document.body.appendChild(particle);
                setTimeout(() => particle.remove(), 4000);
            }
            setInterval(createParticle, 3000);
        </script>
    @endpush
@endsection