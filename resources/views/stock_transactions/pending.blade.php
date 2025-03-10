@extends('layouts.app')

@section('content')
<div class="container mx-auto p-5 mt-6">
    <h1 class="text-2xl font-bold text-gray-800 mb-5 mt-6">📌 Transaksi Pending</h1>

    @if(isset($pendingTransactions) && $pendingTransactions->isNotEmpty())
        <div class="space-y-4">
            @foreach($pendingTransactions as $transaction)
                @php
                    $isHighlighted = request('product_id') == $transaction->product->id;
                @endphp
                <div class="transaction-item bg-white shadow-md rounded-lg p-5 border border-gray-300 flex flex-col md:flex-row justify-between items-start md:items-center gap-4
                            transition-all duration-500 ease-in-out
                            {{ $isHighlighted ? 'bg-yellow-100' : '' }}" 
                     data-product-id="{{ $transaction->product->id }}">
                    <!-- Informasi Transaksi -->
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-gray-700">{{ $transaction->product->name }}</h3>
                        <p class="text-sm text-gray-500">Kuantitas: <span class="font-medium">{{ $transaction->quantity }}</span></p>
                        <p class="text-sm text-gray-500">
                            Status:
                            <span class="px-3 py-1 rounded-md text-xs font-semibold
                                {{ $transaction->status === 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}">
                                {{ $transaction->status }}
                            </span>
                        </p>
                    </div>

                    <!-- Form Konfirmasi + Catatan -->
                    <form action="{{ route('stock_transactions.confirm', $transaction->id) }}" method="POST" class="w-full md:w-auto flex flex-col md:flex-row-reverse items-center gap-3">
                        @csrf
                        <!-- Tombol Konfirmasi -->
                        <button type="submit" 
                            class="px-5 py-2 text-white font-medium rounded-md transition-all duration-300
                                {{ $transaction->type === 'Masuk' ? 'bg-blue-500 hover:bg-blue-600' : 'bg-orange-500 hover:bg-orange-600' }}">
                            {{ $transaction->type === 'Masuk' ? '✔ Konfirmasi Masuk' : 'Konfirmasi Keluar' }}
                        </button>

                        <!-- Textarea Note -->
                        <textarea name="note" rows="2" class="w-full md:w-64 border border-gray-300 rounded-md p-2 text-sm focus:ring focus:ring-blue-200 focus:ring-opacity-50"
                            placeholder="Tambahkan catatan (misal: barang rusak, kurang, dll)">{{ $transaction->note ?? '' }}</textarea>
                    </form>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-5">
            {{ $pendingTransactions->links() }}
        </div>
    @else
        <p class="text-gray-500 border border-gray-300 p-5 rounded-md text-center">Tidak ada transaksi pending.</p>
    @endif
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('product_id');

        if (productId) {
            const highlightedElements = document.querySelectorAll(`.transaction-item[data-product-id='${productId}']`);

            if (highlightedElements.length > 0) {
                highlightedElements[0].scrollIntoView({ behavior: 'smooth', block: 'center' });

                setTimeout(() => {
                    highlightedElements.forEach(element => {
                        element.classList.remove('bg-yellow-100');
                    });
                }, 2000); // Menghilangkan highlight setelah 2 detik
            }
        }
    });
</script>
@endsection
