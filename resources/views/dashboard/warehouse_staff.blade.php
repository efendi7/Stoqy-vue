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
        <h3 class="text-sm font-medium">Tugas Hari Ini</h3>
        <p class="text-base font-bold">{{ date('d M Y') }}</p>
        <p class="text-sm mt-1">
            Transaksi Masuk Pending: <span class="font-bold">{{ count($pendingIncomingTasks ?? []) }}</span> |
            Transaksi Keluar Pending: <span class="font-bold">{{ count($pendingOutgoingTasks ?? []) }}</span>
        </p>
    </div>

    <div class="bg-white p-5 shadow-md rounded-2xl border border-gray-200 w-full">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-600">
                📘 Tugas Baru - Barang Masuk
                <span class="bg-blue-100 text-blue-700 text-xs px-2 py-1 rounded">Jumlah: {{ $incomingTaskStaff->count() }}</span>
            </h2>
        </div>
        <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse ($incomingTaskStaff as $task)
                <li class="bg-blue-50 p-3 rounded-lg flex flex-col hover:bg-blue-100 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <strong class="text-blue-700 truncate">{{ $task->product->name }}</strong>
                        <span class="text-white font-bold bg-blue-700 px-3 py-1 rounded">{{ $task->quantity }}</span>
                    </div>
                    <p class="text-sm text-yellow-600 font-semibold mt-2">
                        <a href="{{ route('stock_transactions.pending', ['product_id' => $task->product->id]) }}" class="underline hover:text-yellow-800">
                            ⚠️ Perlu diperiksa
                        </a>
                    </p>
                    <p class="text-xs text-gray-500 mt-2">{{ $task->created_at->format('d M Y H:i') }}</p>
                </li>
            @empty
                <p class="text-gray-500 text-center w-full col-span-3">Tidak ada tugas barang masuk saat ini.</p>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $incomingTaskStaff->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>

    <div class="bg-white p-5 shadow-md rounded-2xl border border-gray-200 w-full mt-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-600">
                📕 Tugas Baru - Barang Keluar
                <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded">Jumlah: {{ $outgoingTaskStaff->count() }}</span>
            </h2>
        </div>
        <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse ($outgoingTaskStaff as $task)
                <li class="bg-red-50 p-3 rounded-lg flex flex-col hover:bg-red-100 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <strong class="text-red-700 truncate">{{ $task->product->name }}</strong>
                        <span class="text-white font-bold bg-red-700 px-3 py-1 rounded">{{ $task->quantity }}</span>
                    </div>
                    <p class="text-sm text-yellow-600 font-semibold mt-2">
                        <a href="{{ route('stock_transactions.pending', ['product_id' => $task->product->id]) }}" class="underline hover:text-yellow-800">
                            ⚠️ Perlu diperiksa
                        </a>
                    </p>
                    <p class="text-xs text-gray-500 mt-2">{{ $task->created_at->format('d M Y H:i') }}</p>
                </li>
            @empty
                <p class="text-gray-500 text-center w-full col-span-3">Tidak ada tugas barang keluar saat ini.</p>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $outgoingTaskStaff->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>

    <div class="bg-white p-5 shadow-md rounded-2xl border border-gray-200 w-full mt-6">
        <div class="flex items-center justify-between mb-3">
            <h2 class="text-lg font-semibold text-gray-600">
                ✅ Tugas yang Telah Diproses
                <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded">Jumlah: {{ $completeTaskStaff->count() }}</span>
            </h2>
        </div>
        <ul class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @forelse ($completeTaskStaff->items() as $task)
                <li class="bg-green-50 p-3 rounded-lg flex flex-col hover:bg-green-100 hover:shadow-md transition">
                    <div class="flex justify-between items-center">
                        <strong class="text-green-700 truncate">{{ $task->product->name }}</strong>
                        <span class="text-white font-bold bg-green-700 px-3 py-1 rounded">{{ $task->quantity }}</span>
                    </div>
                    <span class="mt-2 text-xs font-semibold px-2 py-1 rounded-md
                        {{ $task->status === 'Diterima' ? 'bg-green-100 text-green-700' :
                           ($task->status === 'Ditolak' ? 'bg-red-100 text-red-700' :
                           'bg-blue-100 text-blue-700') }}">
                        {{ $task->status === 'Confirmed' ? 'Menunggu Keputusan Manajer' : $task->status }}
                    </span>
                    <p class="text-sm text-slate-600 font-semibold mt-2">
                        <a href="{{ route('stock_transactions.confirmed', ['product_id' => $task->product->id]) }}" class="underline hover:text-blue-700">
                            🔍 Periksa
                        </a>
                    </p>
                    <p class="text-xs text-gray-500 mt-2">{{ $task->created_at->format('d M Y H:i') }}</p>
                </li>
            @empty
                <p class="text-gray-500 text-center w-full col-span-3">Tidak ada tugas yang telah diproses saat ini.</p>
            @endforelse
        </ul>

        <div class="mt-4">
            {{ $completeTaskStaff->appends(request()->query())->links('vendor.pagination.custom') }}
        </div>
    </div>
</div>
@endsection