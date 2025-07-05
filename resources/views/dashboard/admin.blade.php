@extends('layouts.app')

@section('content')
    <div
        {{-- PERUBAHAN: Menambahkan gradient untuk light mode --}}
        class="min-h-screen p-4 relative overflow-hidden text-gray-900 
               bg-gradient-to-br from-white to-gray-100
               dark:bg-gradient-to-br dark:from-[#0A0E15] dark:via-[#0F1419] dark:to-[#1A1F2E] dark:text-gray-100">

        {{-- 1. Panggil Komponen Animated Background --}}
        <x-dashboard.animated-background />

        <div class="container mx-auto px-4 mt-8 relative z-10">

            {{-- 2. Panggil Komponen Welcome Banner --}}
            <x-dashboard.welcome-banner :userRoleLabel="$userRoleLabel" :userName="$userName" />

            <form action="{{ route('dashboard') }}" method="GET" id="dashboardForm">
                {{-- (Bagian form filter Anda bisa tetap di sini atau dijadikan komponen juga) --}}
                @include('partials.dashboard-admin.period-filter') {{-- Asumsi ini partial --}}

                {{-- 3. Panggil Komponen Metric Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    @foreach ($metrics as $metric)
                        <x-dashboard.metric-card :label="$metric['label']" :value="$metric['value']" :gradient="$metric['gradient']" :border="$metric['border']"
                            :text="$metric['text']" :icon="$metric['icon']" :delay="$metric['delay']" :dark_gradient="$metric['dark_gradient']" :dark_border="$metric['dark_border']"
                            :dark_text="$metric['dark_text']" />
                    @endforeach
                </div>

                {{-- 4. Panggil Komponen Chart --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                    <x-dashboard.chart-card title="Stok Barang" icon="📊" :hasData="!empty($stockData)">
                        {{-- (Konten filter chart & canvas di sini) --}}
                        <canvas id="stockChart"></canvas>
                    </x-dashboard.chart-card>

                    <x-dashboard.chart-card title="Laporan Transaksi" icon="📈" :hasData="!empty($transactionLabels)">
                        {{-- (Konten canvas di sini) --}}
                        <canvas id="transactionReportChart"></canvas>
                    </x-dashboard.chart-card>
                </div>
            </form>

            {{-- (Bagian Aktivitas Hari Ini) --}}
            @include('partials.dashboard-admin.activity-log') {{-- Asumsi ini partial --}}
        </div>
    </div>
@endsection

@push('scripts')
    {{-- 
     "Jembatan Data" dari PHP (Server) ke JavaScript (Client).
    --}}
    <script>
        // Safely pass data to JavaScript with fallbacks
        const stockLabels = @json($stockLabels ?? []);
        const stockData = @json($stockData ?? []);
        const transactionLabels = @json($transactionLabels ?? []);
        const incomingTransactionData = @json($incomingTransactionData ?? []);
        const outgoingTransactionData = @json($outgoingTransactionData ?? []);
        const combinedTransactionData = @json($combinedTransactionData ?? []);

        // Initialize charts if data exists
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize stock chart
            if (stockLabels.length > 0 && stockData.length > 0) {
                initializeStockChart();
            }

            // Initialize transaction chart
            if (transactionLabels.length > 0 && (incomingTransactionData.length > 0 || outgoingTransactionData
                    .length > 0)) {
                initializeTransactionChart();
            }
        });

        // Stock Chart Function (implement based on your chart library)
        function initializeStockChart() {
            // Add your stock chart initialization code here
            console.log('Stock chart data:', {
                stockLabels,
                stockData
            });
        }

        // Transaction Chart Function (implement based on your chart library)
        function initializeTransactionChart() {
            // Add your transaction chart initialization code here
            console.log('Transaction chart data:', {
                transactionLabels,
                incomingTransactionData,
                outgoingTransactionData
            });
        }
    </script>
@endpush