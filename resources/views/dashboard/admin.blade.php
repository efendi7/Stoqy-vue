@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-20"> {{-- Menambahkan margin-top agar tidak tertimpa navbar --}}

    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-base md:text-2xl font-bold">
        Selamat datang
        <span class="capitalize">{{ ucfirst(app(\App\Services\UserService::class)->getRoleLabel(auth()->user()->role)) }}</span>,
        {{ auth()->user()->name }}!
        </h2>
    </div>

    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-gray-600 text-sm font-medium mb-4">Filter Periode</h3>
        <form action="{{ route('dashboard') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <label for="start_date" class="mr-2 text-sm">Dari:</label>
                <input type="date" id="start_date" name="start_date" value="{{ $startDate ?? date('Y-m-d', strtotime('-30 days')) }}"
                    class="border rounded px-2 py-1 text-sm">
            </div>
            <div class="flex items-center">
                <label for="end_date" class="mr-2 text-sm">Sampai:</label>
                <input type="date" id="end_date" name="end_date" value="{{ $endDate ?? date('Y-m-d') }}"
                    class="border rounded px-2 py-1 text-sm">
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded text-sm hover:bg-blue-600">
                Terapkan
            </button>
        </form>
    </div>

    <div class="bg-indigo-100 text-indigo-800 p-4 rounded-lg shadow-md mb-6">
        <h3 class="text-sm font-medium">Informasi Periode</h3>
        <p class="text-base font-bold">{{ date('d M Y', strtotime($startDate ?? '-30 days')) }} -
        {{ date('d M Y', strtotime($endDate ?? 'now')) }}</p>
        <p class="text-sm mt-1">
            Transaksi Masuk: <span class="font-bold">{{ $incomingTransactions }}</span> |
            Transaksi Keluar: <span class="font-bold">{{ $outgoingTransactions }}</span>
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $commonMetrics = [
                ['label' => 'Total Produk', 'value' => $totalProducts, 'color' => 'bg-green-100 text-green-800', 'icon' => '📦'],
                ['label' => 'Stok Tersedia', 'value' => $availableStock, 'color' => 'bg-green-100 text-green-800', 'icon' => '✅'],
                ['label' => 'Stok Rendah', 'value' => $lowStockItems, 'color' => 'bg-red-100 text-red-800', 'icon' => '⚠️'],
                ['label' => 'Stok Habis', 'value' => $outOfStock, 'color' => 'bg-red-100 text-red-800', 'icon' => '❌'],
            ];

            $adminTransactionMetrics = [
                ['label' => 'Transaksi Masuk', 'value' => $incomingTransactions ?? 0, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '📥'],
                ['label' => 'Transaksi Keluar', 'value' => $outgoingTransactions ?? 0, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '📤'],
            ];

            $adminMetrics = [
                ['label' => 'Total Users', 'value' => $totalUsers, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '👥'],
                ['label' => 'Total Suppliers', 'value' => $totalSuppliers, 'color' => 'bg-green-100 text-green-800', 'icon' => '🏭'],
                ['label' => 'Active Users', 'value' => $activeUsers, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⚡'],
            ];

            $metrics = array_merge($commonMetrics, $adminMetrics, $adminTransactionMetrics);
        @endphp

        @foreach($metrics as $metric)
            <div class="p-6 rounded-lg shadow-md {{ $metric['color'] }}">
                <div class="flex items-center">
                    <span class="text-4xl">{{ $metric['icon'] }}</span>
                    <div class="ml-4">
                        <p class="text-lg font-semibold">{{ $metric['label'] }}</p>
                        <p class="text-xl">{{ $metric['value'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Stok Barang</h3>
            <canvas id="stockChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Semua Transaksi</h3>
            <canvas id="combinedTransactionChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Transaksi Masuk</h3>
            <canvas id="incomingTransactionChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Transaksi Keluar</h3>
            <canvas id="outgoingTransactionChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <div id="activity-section" class="bg-white p-4 rounded-md shadow-md text-sm">
        <h3 class="text-gray-700 font-semibold mb-3 text-base">Aktivitas Hari Ini</h3>

        @if($recentActivities->isNotEmpty())
            <div class="border rounded-md overflow-hidden">
                <div class="bg-gray-100 text-gray-700 font-medium px-3 py-2 flex text-xs">
                    <span class="w-2/5">Aksi</span>
                    <span class="w-1/4 text-center">Waktu</span>
                    <span class="w-1/3 text-right pr-3">Pengguna</span>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($recentActivities as $activity)
                        <div class="px-3 py-2 flex items-center text-xs">
                            <div class="w-2/5 truncate">
                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-600">
                                    {{ $activity->action ?? 'Tidak ada aksi' }}
                                </span>
                            </div>
                            <div class="w-1/4 text-center text-gray-500 truncate">
                                {{ $activity->created_at->diffForHumans() }}
                            </div>
                            <div class="w-1/3 flex justify-end">
                                <span class="px-2 py-1 rounded bg-gray-100 text-gray-600">
                                    {{ $activity->user->name }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4 flex justify-center text-xs">
                {{ $recentActivities->appends(request()->query())->links('vendor.pagination.custom') }}
            </div>
        @else
            <p class="text-gray-500 text-center text-xs">Tidak ada aktivitas untuk hari ini.</p>
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    try {
        // Stock Chart
        const stockCtx = document.getElementById('stockChart');
        if (stockCtx) {
            new Chart(stockCtx.getContext('2d'), {
                type: 'bar',
                data: {
                    labels: {!! json_encode($stockLabels ?? []) !!},
                    datasets: [{
                        label: 'Stok Barang',
                        data: {!! json_encode($stockData ?? []) !!},
                        backgroundColor: 'rgba(34, 197, 94, 0.2)',
                        borderColor: 'rgba(34, 197, 94, 1)',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Incoming Transaction Chart
        const incomingTransactionCtx = document.getElementById('incomingTransactionChart');
        if (incomingTransactionCtx) {
            new Chart(incomingTransactionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionLabels ?? []) !!},
                    datasets: [
                        {
                            label: 'Transaksi Masuk',
                            data: {!! json_encode($incomingTransactionData ?? []) !!},
                            borderColor: 'rgba(59, 130, 246, 1)',
                            backgroundColor: 'rgba(59, 130, 246, 0.2)',
                            borderWidth: 2,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                precision: 0
                            },
                            suggestedMin: 1,
                            suggestedMax: 10
                        }
                    }
                }
            });
        }

        // Outgoing Transaction Chart
        const outgoingTransactionCtx = document.getElementById('outgoingTransactionChart');
        if (outgoingTransactionCtx) {
            new Chart(outgoingTransactionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionLabels ?? []) !!},
                    datasets: [
                        {
                            label: 'Transaksi Keluar',
                            data: {!! json_encode($outgoingTransactionData ?? []) !!},
                            borderColor: 'rgba(220, 38, 38, 1)',
                            backgroundColor: 'rgba(220, 38, 38, 0.2)',
                            borderWidth: 1,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                precision: 0
                            },
                            suggestedMin: 1,
                            suggestedMax: 10
                        }
                    }
                }
            });
        }

        // Combined Transaction Chart
        const combinedTransactionCtx = document.getElementById('combinedTransactionChart');
        if (combinedTransactionCtx) {
            new Chart(combinedTransactionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionLabels ?? []) !!},
                    datasets: [
                        {
                            label: 'Transaksi Masuk + Transaksi Keluar',
                            data: {!! json_encode($combinedTransactionData ?? []) !!},
                            borderColor: 'rgba(128, 0, 128, 1)', // Warna ungu solid untuk garis
                            backgroundColor: 'rgba(128, 0, 128, 0.2)', // Warna ungu transparan untuk area di bawah garis
                            borderWidth: 2,
                            fill: true
                        }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: false,
                            ticks: {
                                precision: 0
                            },
                            suggestedMin: 1,
                            suggestedMax: 10
                        }
                    },
                    plugins: {
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const index = context.dataIndex;
                                    const incomingCount = {!! json_encode($incomingTransactionData ?? []) !!}[index];
                                    const outgoingCount = {!! json_encode($outgoingTransactionData ?? []) !!}[index];
                                    return [
                                        'Total Transaksi: ' + context.raw,
                                        'Transaksi Masuk: ' + incomingCount,
                                        'Transaksi Keluar: ' + outgoingCount
                                    ];
                                }
                            }
                        }
                    }
                }
            });
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
</script>
@endpush

@endsection