@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Dashboard</h1>
    
    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
            <p class="text-2xl font-bold">{{ $totalProducts }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium">Low Stock Items</h3>
            <p class="text-2xl font-bold">{{ $lowStockItems }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium">Incoming Transactions</h3>
            <p class="text-2xl font-bold">{{ $incomingTransactions }}</p>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium">Outgoing Transactions</h3>
            <p class="text-2xl font-bold">{{ $outgoingTransactions }}</p>
        </div>

        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium">Active Users</h3>
            <p class="text-2xl font-bold">{{ $activeUsers }}</p>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium mb-4">Stock Levels</h3>
            <canvas id="stockChart" class="w-full h-64"></canvas>
        </div>
        <div class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-gray-500 text-sm font-medium mb-4">Transaction Trends</h3>
            <canvas id="transactionChart" class="w-full h-64"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="bg-white p-6 rounded-lg shadow">
        <h3 class="text-gray-500 text-sm font-medium mb-4">Recent Activities</h3>
        <div class="space-y-4">
            @foreach($recentActivities as $activity)
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm">{{ $activity->description }}</p>
                    <p class="text-xs text-gray-500">{{ $activity->created_at->diffForHumans() }}</p>
                </div>
                <span class="text-xs px-2 py-1 rounded-full bg-gray-100">{{ $activity->user->name }}</span>
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
                        backgroundColor: 'rgba(79, 70, 229, 0.2)',
                        borderColor: 'rgba(79, 70, 229, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
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
                        borderColor: 'rgba(99, 102, 241, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
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
