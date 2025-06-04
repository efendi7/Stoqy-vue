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

    <div class="bg-indigo-100 text-indigo-800 p-4 rounded-lg shadow-md mb-6">
        <h3 class="text-sm font-medium">Informasi Hari Ini</h3>
        <p class="text-base font-bold">{{ date('d M Y') }}</p>
        <p class="text-sm mt-1">
            Transaksi Masuk Hari Ini: <span class="font-bold">{{ $todayIncomingTransactions }}</span> |
            Transaksi Keluar Hari Ini: <span class="font-bold">{{ $todayOutgoingTransactions }}</span>
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

            $whManagerMetrics = [
                ['label' => 'Transaksi Masuk Hari Ini', 'value' => $todayIncomingTransactions ?? 0, 'color' => 'bg-blue-100 text-blue-800', 'icon' => '📥'],
                ['label' => 'Transaksi Keluar Hari Ini', 'value' => $todayOutgoingTransactions ?? 0, 'color' => 'bg-yellow-100 text-yellow-800', 'icon' => '📤'],
            ];

            $metrics = array_merge($commonMetrics, $whManagerMetrics);
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
</div>
@endsection