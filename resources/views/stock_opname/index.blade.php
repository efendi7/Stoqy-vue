@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Stok Opname</h1>

    @if(session('success'))
        <div id="flash-success" class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">  
            <div class="flex items-center space-x-2">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
        </div>
        <script>
            setTimeout(() => {
                let flashSuccess = document.getElementById('flash-success');
                if (flashSuccess) {
                    flashSuccess.style.opacity = '0';
                    setTimeout(() => flashSuccess.remove(), 500);
                }
            }, 4000);
        </script>
    @endif

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden border border-gray-300">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 text-center px-4 border border-gray-300">No</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Nama Produk</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Stok Tercatat</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Stok Fisik</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Selisih</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($products as $index => $product)
                    @php
                        $stockOpname = $product->stockOpname; // Pastikan ada relasi di model Product
                        $actualStock = $stockOpname->actual_stock ?? $product->stock;
                        $difference = $stockOpname->difference ?? 0;
                    @endphp
                    <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                        <td class="py-3 px-4 border text-center border-gray-300">{{ $index + 1 }}</td>
                        <td class="py-3 px-4 border text-left border-gray-300">{{ $product->name }}</td>
                        <td class="py-3 px-4 border text-center border-gray-300" id="recorded_stock_{{ $product->id }}">{{ $product->stock }}</td>
                        <td class="py-3 px-4 border text-center border-gray-300">
                            <input type="number" name="actual_stock" id="actual_stock_{{ $product->id }}" 
                                class="border text-center p-1 w-full"
                                value="{{ $actualStock }}"
                                min="0"
                                oninput="calculateDifference({{ $product->id }})">
                        </td>
                        <td class="py-3 px-4 text-center border border-gray-300" id="difference_{{ $product->id }}">{{ $difference }}</td>
                        <td class="py-3 px-4 text-center border border-gray-300">
                            <form action="{{ route('stock_opname.store') }}" method="POST" onsubmit="return setAuditValues({{ $product->id }})">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <input type="hidden" name="recorded_stock" id="recorded_stock_hidden_{{ $product->id }}" value="{{ $product->stock }}">
                                <input type="hidden" name="actual_stock" id="actual_stock_hidden_{{ $product->id }}" value="{{ $actualStock }}">
                                <input type="hidden" name="difference" id="difference_hidden_{{ $product->id }}" value="{{ $difference }}">
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">Save Audit</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    function calculateDifference(productId) {
        let recordedStock = parseInt(document.getElementById(`recorded_stock_${productId}`).innerText);
        let actualStock = parseInt(document.getElementById(`actual_stock_${productId}`).value) || 0;
        let stockDifference = actualStock - recordedStock;

        // Update difference display
        document.getElementById(`difference_${productId}`).innerText = stockDifference;

        // Update hidden inputs for audit form
        document.getElementById(`actual_stock_hidden_${productId}`).value = actualStock;
        document.getElementById(`difference_hidden_${productId}`).value = stockDifference;
    }

    function setAuditValues(productId) {
        let recordedStock = parseInt(document.getElementById(`recorded_stock_${productId}`).innerText);
        let actualStock = parseInt(document.getElementById(`actual_stock_${productId}`).value) || 0;
        let stockDifference = actualStock - recordedStock;

        // Ensure hidden inputs are set correctly before form submission
        document.getElementById(`recorded_stock_hidden_${productId}`).value = recordedStock;
        document.getElementById(`actual_stock_hidden_${productId}`).value = actualStock;
        document.getElementById(`difference_hidden_${productId}`).value = stockDifference;
    }
</script>
@endsection
