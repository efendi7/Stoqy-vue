@extends('layouts.app')

@section('content')
<div class="container mx-auto p-5">
    <h1 class="text-2xl font-bold mb-4">📌 Transaksi Pending</h1>

    @if(isset($pendingTransactions) && $pendingTransactions->isNotEmpty())
        <div class="space-y-4">
            @foreach($pendingTransactions as $transaction)
                <div class="bg-white shadow-md rounded-lg p-5 border border-gray-200 flex justify-between items-center">
                    <!-- Informasi Transaksi -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-700">{{ $transaction->product->name }}</h3>
                        <p class="text-sm text-gray-500">Kuantitas: <span class="font-medium">{{ $transaction->quantity }}</span></p>
                        <p class="text-sm text-gray-500">
                            Status:
                            <span @class([
                                'px-3 py-1 rounded-md text-xs font-semibold',
                                'bg-yellow-100 text-yellow-800' => $transaction->status === 'Pending',
                            ])>
                                {{ $transaction->status }}
                            </span>
                        </p>
                    </div>

                    <!-- Form Konfirmasi + Catatan -->
                    <form action="{{ route('stock_transactions.confirm', $transaction->id) }}" method="POST" class="flex flex-col items-end">
                        @csrf
                        <textarea name="note" rows="2" class="w-full md:w-64 border-gray-300 rounded-md p-2 text-sm focus:ring focus:ring-blue-200"
                            placeholder="Tambahkan catatan (misal: barang rusak, kurang, dll)">{{ $transaction->note ?? '' }}</textarea>

                        <!-- Tombol Konfirmasi -->
                        <button type="submit" 
                            @class([
                                'mt-2 px-5 py-2 text-white font-medium rounded-md',
                                'bg-blue-500 hover:bg-blue-600' => $transaction->type === 'Masuk',
                                'bg-orange-500 hover:bg-orange-600' => $transaction->type === 'Keluar',
                            ])>
                            {{ $transaction->type === 'Masuk' ? 'Konfirmasi Masuk' : 'Konfirmasi Keluar' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $pendingTransactions->links() }}
        </div>
    @else
        <p class="text-gray-500 border border-gray-300 p-5 rounded-md">Tidak ada transaksi pending.</p>
    @endif
</div>
@endsection
