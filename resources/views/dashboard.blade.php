@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 mt-20"> {{-- Menambahkan margin-top agar tidak tertimpa navbar --}}
    
    <!-- Welcome Message -->
    <div class="bg-blue-100 text-blue-800 p-4 rounded-lg shadow-md mb-6">
        <h2 class="text-base md:text-2xl font-bold">
            Selamat datang <span class="capitalize">{{ ucfirst(auth()->user()->role) }}</span>, {{ auth()->user()->name }}!
        </h2>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        @php
            $metrics = [
                ['label' => 'Total Products', 'value' => $totalProducts, 'color' => 'bg-green-100 text-green-800', 'icon' => '📦'],
                ['label' => 'Low Stock Items', 'value' => $lowStockItems, 'color' => 'bg-red-100 text-red-800', 'icon' => '⚠️'],
                ['label' => 'Incoming Transactions', 'value' => $incomingTransactions, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '📥'],
                ['label' => 'Outgoing Transactions', 'value' => $outgoingTransactions, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '📤'],
                ['label' => 'Active Users', 'value' => $activeUsers, 'color' => 'bg-purple-100 text-purple-800', 'icon' => '👥'],
            ];
        @endphp

        @foreach($metrics as $metric)
        <div class="p-6 rounded-lg shadow-md {{ $metric['color'] }}">
            <div class="flex items-center">
                <span class="text-4xl">{{ $metric['icon'] }}</span>
                <div class="ml-4">
                    <h3 class="text-sm font-medium">{{ $metric['label'] }}</h3>
                    <p class="text-2xl font-bold">{{ $metric['value'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Stock Levels</h3>
            <canvas id="stockChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow-md">
            <h3 class="text-gray-600 text-sm font-medium mb-4">Transaction Trends</h3>
            <canvas id="transactionChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white p-6 rounded-lg shadow-md">
        <h3 class="text-gray-600 text-sm font-medium mb-4">Recent Activities</h3>
        <div class="divide-y divide-gray-200">
            @foreach($recentActivities as $activity)
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
                    labels: {!! json_encode($stockLabels) !!},
                    datasets: [{
                        label: 'Stock Levels',
                        data: {!! json_encode($stockData) !!},
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
        } else {
            console.error('Stock chart canvas element not found');
        }

        // Transaction Chart
        const transactionCtx = document.getElementById('transactionChart');
        if (transactionCtx) {
            new Chart(transactionCtx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($transactionLabels) !!},
                    datasets: [{
                        label: 'Transactions',
                        data: {!! json_encode($transactionData) !!},
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: 'rgba(59, 130, 246, 0.2)',
                        borderWidth: 2,
                        fill: true
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
        } else {
            console.error('Transaction chart canvas element not found');
        }
    } catch (error) {
        console.error('Error initializing charts:', error);
    }
</script>
@endpush

@endsection
