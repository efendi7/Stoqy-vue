@extends('layouts.app')

@section('content')
<div class="container mx-auto p-5 mt-6">
    <h1 class="text-2xl font-bold mb-4 mt-6">✅ Transaksi yang Sudah Dikonfirmasi</h1>

    @if($confirmedTransactions->isNotEmpty())
        <div class="space-y-4">
            @foreach($confirmedTransactions as $transaction)
                <div class="bg-white shadow-md rounded-lg p-5 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $transaction->product->name }}</h3>
                    <p class="text-sm text-gray-500">Kuantitas: <span class="font-medium">{{ $transaction->quantity }}</span></p>
                    <p class="text-sm text-gray-500">
                        Status:
                        <span @class([
                            'font-medium px-3 py-1 rounded-md text-xs',
                            'bg-blue-100 text-blue-600' => $transaction->status === 'Confirmed',
                            'bg-green-100 text-green-600' => $transaction->status === 'Diterima',
                            'bg-red-100 text-red-600' => $transaction->status === 'Ditolak',
                        ])>
                            {{ $transaction->status }}
                        </span>
                    </p>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $confirmedTransactions->links() }} {{-- Pagination --}}
        </div>
    @else
        <p class="text-gray-500 borders border-gray-300 p-5 rounded-md">Belum ada transaksi yang dikonfirmasi.</p>
    @endif
</div>
@endsection
