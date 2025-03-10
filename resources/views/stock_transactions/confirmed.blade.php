@extends('layouts.app')

@section('content')
<div class="container mx-auto p-5 mt-6">
    <h1 class="text-2xl font-bold mb-4 mt-6">✅ Transaksi yang Sudah Dikonfirmasi</h1>

    @if($confirmedTransactions->isNotEmpty())
        <div class="space-y-4">
            @foreach($confirmedTransactions as $transaction)
                @php
                    $isHighlighted = request('product_id') == $transaction->product->id;
                @endphp
                <div class="transaction-item bg-white shadow-md rounded-lg p-5 border border-gray-200
                            transition-all duration-500 ease-in-out
                            {{ $isHighlighted ? 'bg-yellow-100' : '' }}" 
                     data-product-id="{{ $transaction->product->id }}">

                    <h3 class="text-lg font-semibold text-gray-700">{{ $transaction->product->name }}</h3>
                    <p class="text-sm text-gray-500">Kuantitas: <span class="font-medium">{{ $transaction->quantity }}</span></p>
                    <p class="text-sm text-gray-500">
                        Status:
                        <span class="font-medium px-3 py-1 rounded-md text-xs
                            {{ $transaction->status === 'Confirmed' ? 'bg-blue-100 text-blue-600' : '' }}
                            {{ $transaction->status === 'Diterima' ? 'bg-green-100 text-green-600' : '' }}
                            {{ $transaction->status === 'Ditolak' ? 'bg-red-100 text-red-600' : '' }}">
                            {{ $transaction->status }}
                        </span>
                    </p>
                    <p class="text-sm text-gray-500">
                        Catatan: <span class="font-medium">{{ $transaction->note ?? 'Tidak ada catatan' }}</span>
                    </p>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $confirmedTransactions->links() }} {{-- Pagination --}}
        </div>
    @else
        <p class="text-gray-500 border border-gray-300 p-5 rounded-md">Belum ada transaksi yang dikonfirmasi.</p>
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
