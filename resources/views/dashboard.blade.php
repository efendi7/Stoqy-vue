@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-20"> {{-- Menambahkan margin-top agar tidak tertimpa navbar --}}
    
    <!-- Welcome Message for All Roles -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-base md:text-2xl font-bold">
        Selamat datang 
    <span class="capitalize">{{ ucfirst(app(\App\Services\UserService::class)->getRoleLabel(auth()->user()->role)) }}</span>, 
    {{ auth()->user()->name }}!
        </h2>
    </div>

    @if(auth()->user()->role === 'admin')
        <!-- Periode Filter - Admin Only -->
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

        <!-- Period Info - Admin Only -->
        <div class="bg-indigo-100 text-indigo-800 p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-sm font-medium">Informasi Periode</h3>
            <p class="text-base font-bold">{{ date('d M Y', strtotime($startDate ?? '-30 days')) }} - 
            {{ date('d M Y', strtotime($endDate ?? 'now')) }}</p>
            <p class="text-sm mt-1">
                Transaksi Masuk: <span class="font-bold">{{ $incomingTransactions }}</span> | 
                Transaksi Keluar: <span class="font-bold">{{ $outgoingTransactions }}</span>
            </p>
        </div>
    @elseif(auth()->user()->role === 'warehouse_manager')
        <!-- Period Info - Warehouse Manager (Today Only) -->
        <div class="bg-indigo-100 text-indigo-800 p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-sm font-medium">Informasi Hari Ini</h3>
            <p class="text-base font-bold">{{ date('d M Y') }}</p>
            <p class="text-sm mt-1">
                Transaksi Masuk Hari Ini: <span class="font-bold">{{ $todayIncomingTransactions }}</span> | 
                Transaksi Keluar Hari Ini: <span class="font-bold">{{ $todayOutgoingTransactions }}</span>
            </p>
        </div>
    @elseif(auth()->user()->role === 'warehouse_staff')
        <!-- Task Info - Warehouse Staff -->
        <div class="bg-indigo-100 text-indigo-800 p-4 rounded-lg shadow-md mb-6">
            <h3 class="text-sm font-medium">Tugas Hari Ini</h3>
            <p class="text-base font-bold">{{ date('d M Y') }}</p>
            <p class="text-sm mt-1">
                Transaksi Masuk Pending: <span class="font-bold">{{ count($pendingIncomingTasks ?? []) }}</span> | 
                Transaksi Keluar Pending: <span class="font-bold">{{ count($pendingOutgoingTasks ?? []) }}</span>
            </p>
        </div>
    @endif

    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'warehouse_manager')
        <!-- Key Metrics - Admin & Warehouse Manager -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    @php
        // Common metrics for both Admin and Warehouse Manager
        $commonMetrics = [
            ['label' => 'Total Produk', 'value' => $totalProducts, 'color' => 'bg-green-100 text-green-800', 'icon' => '📦'],
            ['label' => 'Stok Tersedia', 'value' => $availableStock, 'color' => 'bg-green-100 text-green-800', 'icon' => '✅'], 
            ['label' => 'Stok Rendah', 'value' => $lowStockItems, 'color' => 'bg-red-100 text-red-800', 'icon' => '⚠️'],
            ['label' => 'Stok Habis', 'value' => $outOfStock, 'color' => 'bg-red-100 text-red-800', 'icon' => '❌'],
        ];
        
        // Warehouse Manager transaction metrics (today only)
        $whManagerMetrics = [
            ['label' => 'Transaksi Masuk Hari Ini', 'value' => $todayIncomingTransactions ?? 0, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '📥'],
            ['label' => 'Transaksi Keluar Hari Ini', 'value' => $todayOutgoingTransactions ?? 0, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '📤'],
        ];
        
        // Admin transaction metrics (period)
        $adminTransactionMetrics = [
            ['label' => 'Transaksi Masuk', 'value' => $incomingTransactions ?? 0, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '📥'],
            ['label' => 'Transaksi Keluar', 'value' => $outgoingTransactions ?? 0, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '📤'],
        ];

        // Admin specific metrics
        $adminMetrics = [
            ['label' => 'Total Users', 'value' => $totalUsers, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '👥'],
            ['label' => 'Total Suppliers', 'value' => $totalSuppliers, 'color' => 'bg-green-100 text-green-800', 'icon' => '🏭'],
            ['label' => 'Active Users', 'value' => $activeUsers, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '⚡'],
            // Add more metrics as needed
        ];

        // Define which metrics to show based on role
        $metrics = $commonMetrics;
        
        if(auth()->user()->role === 'admin') {
            $metrics = array_merge($metrics, $adminMetrics, $adminTransactionMetrics);
        } elseif(auth()->user()->role === 'warehouse_manager') {
            $metrics = array_merge($metrics, $whManagerMetrics);
        }
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

    @endif

    @if(auth()->user()->role === 'warehouse_staff')
    <!-- Tugas Staff -->
    <div class="grid grid-cols-1 gap-6">
        <!-- Incoming Tasks -->
 <!-- Incoming Tasks -->
<div class="bg-white p-5 shadow-md rounded-2xl border border-gray-200">
    <div class="flex items-center gap-3 mb-3">
        <svg class="w-8 h-8 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z"></path>
            <path d="M8 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <h2 class="text-lg font-semibold text-gray-800">Tugas Baru - Barang Masuk</h2>
    </div>
    <ul class="space-y-2">
        @forelse ($incomingTaskStaff as $task)
            <li class="bg-gray-100 p-3 rounded-lg flex justify-between items-center">
                <div>
                    <span>📦 <strong>{{ $task->product->name }}</strong> ({{ $task->quantity }})</span>
                    <p class="text-sm text-yellow-600 font-semibold">⚠️ Perlu diperiksa</p>
                </div>
                <p class="text-xs text-gray-500">{{ $task->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
            </li>
        @empty
            <p class="text-sm text-gray-500 text-center">Tidak ada tugas barang masuk saat ini.</p>
        @endforelse
    </ul>
</div>

<!-- Outgoing Tasks -->
<div class="bg-white p-5 shadow-md rounded-2xl border border-gray-200">
    <div class="flex items-center gap-3 mb-3">
        <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z"></path>
            <path d="M8 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>
        </svg>
        <h2 class="text-lg font-semibold text-gray-800">Tugas Baru - Barang Keluar</h2>
    </div>
    <ul class="space-y-2">
        @forelse ($outgoingTaskStaff as $task)
            <li class="bg-gray-100 p-3 rounded-lg flex justify-between items-center">
                <div>
                    <span>📤 <strong>{{ $task->product->name }}</strong> ({{ $task->quantity }})</span>
                    <p class="text-sm text-yellow-600 font-semibold">⚠️ Perlu diperiksa</p>
                </div>
                <p class="text-xs text-gray-500">{{ $task->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
            </li>
        @empty
            <p class="text-sm text-gray-500 text-center">Tidak ada tugas barang keluar saat ini.</p>
        @endforelse
    </ul>
</div>


        <!-- Completed Tasks -->
        <div class="bg-white p-5 shadow-lg rounded-2xl border border-gray-200 hover:shadow-xl transition">
            <div class="flex items-center gap-3 mb-3">
                <svg class="w-8 h-8 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2a10 10 0 100 20 10 10 0 000-20zm0 18a8 8 0 110-16 8 8 0 010 16z"></path>
                    <path d="M8 12l2 2 4-4" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
                <h2 class="text-lg font-semibold text-gray-800">Tugas yang Telah Dikonfirmasi</h2>
            </div>
            <ul class="space-y-2">
                @forelse ($completeTaskStaff as $task)
                    <li class="bg-gray-100 p-3 rounded-lg flex justify-between items-center">
                        <span>✅ <strong>{{ $task->product->name }}</strong> ({{ $task->quantity }})</span>
                        <p class="text-xs text-gray-500">{{ $task->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
                    </li>
                @empty
                    <p class="text-sm text-gray-500 text-center">Tidak ada tugas yang telah dikonfirmasi saat ini.</p>
                @endforelse
            </ul>
        </div>
    </div>
@endif


    @if(auth()->user()->role === 'warehouse_manager')
    <!-- Pending Transactions Section - Warehouse Manager -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">


        <!-- Pending Incoming Transactions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Transaksi Masuk Pending</h3>
            <div class="divide-y divide-gray-200">
                @forelse($pendingIncomingTasks ?? [] as $transaction)
                <div class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $transaction->reference_number }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-700 font-semibold">Produk: {{ $transaction->product->name ?? 'Tidak Diketahui' }}</p>

                        </div>
                        
                    </div>
                    
                </div>
                @empty
                <p class="text-sm text-gray-500 italic">Tidak ada transaksi masuk pending</p>
                @endforelse
            </div>
            @if(isset($pendingIncomingTasks) && count($pendingIncomingTasks) > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('transactions.incoming.index', ['status' => 'pending']) }}" 
                   class="text-sm text-blue-600 hover:underline">
                   Lihat Semua
                </a>
            </div>
            @endif
        </div>

        <!-- Pending Outgoing Transactions -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Transaksi Keluar Pending</h3>
            <div class="divide-y divide-gray-200">
                @forelse($pendingOutgoingTasks ?? [] as $transaction)
                <div class="py-3">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $transaction->reference_number }}</p>
                            <p class="text-xs text-gray-500">{{ $transaction->created_at->setTimezone('Asia/Jakarta')->format('d M Y H:i') }}</p>
                            <p class="text-xs text-gray-700 font-semibold">
    Produk: {{ $transaction->product->name ?? 'Tidak Diketahui' }}
</p>



                        </div>
                    </div>
                @empty
                <p class="text-sm text-gray-500 italic">Tidak ada transaksi keluar pending</p>
                @endforelse
            </div>
            @if(isset($pendingOutgoingTasks) && count($pendingOutgoingTasks) > 5)
            <div class="mt-4 text-center">
                <a href="{{ route('transactions.outgoing.index', ['status' => 'pending']) }}" 
                   class="text-sm text-blue-600 hover:underline">
                   Lihat Semua
                </a>
            </div>
            @endif
        </div>
    </div>
@endif

        
    @if(auth()->user()->role === 'admin')
        <!-- Charts Section - Admin Only -->
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

        <!-- Recent Activities - Admin Only -->
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Recent Activities</h3>
            <div class="divide-y divide-gray-200">
                @foreach($recentActivities ?? [] as $activity)
                <div class="py-3 flex justify-between items-center">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $activity->description }}</p>
                        <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                    </div>
                    <span class="text-xs px-3 py-1 rounded-full bg-gray-100 text-gray-600">{{ $activity->user->name }}</span>
                </div>
                @endforeach
            </div>
        </div>
    @endif
</div>

@if(auth()->user()->role === 'admin')
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
                            borderColor: 'rgba(75, 192, 192, 1)',
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
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
@endif

@endsection