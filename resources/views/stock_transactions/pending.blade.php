@extends('layouts.app')

@section('content')
<div class="container mx-auto p-5">
    <h1 class="text-2xl font-bold mb-4">📌 Transaksi Pending</h1>

    @if($pendingTransactions->isNotEmpty())
        <div class="space-y-4">
            @foreach($pendingTransactions as $transaction)
                <div class="bg-white shadow-md rounded-lg p-5 border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-700">{{ $transaction->product->name }}</h3>
                    <p class="text-sm text-gray-500">Kuantitas: <span class="font-medium">{{ $transaction->quantity }}</span></p>
                    <p class="text-sm">
                        Status:
                        <span class="px-3 py-1 rounded-md text-xs font-semibold bg-yellow-100 text-yellow-800">
                            {{ $transaction->status }}
                        </span>
                    </p>
                    <form action="{{ route('stock_transactions.confirm', $transaction->id) }}" method="POST">
                        @csrf
                        <button type="submit" 
                            class="px-5 py-2 text-white font-medium rounded-md bg-blue-500 hover:bg-blue-600">
                            Konfirmasi
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
        <div class="mt-4">
            {{ $pendingTransactions->links() }} {{-- Pagination --}}
        </div>
    @else
        <p class="text-gray-500 border border-gray-300 p-5 rounded-md">Tidak ada transaksi pending.</p>
    @endif
</div>
@endsection
