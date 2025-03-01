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
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">No</th>
                    <th class="py-3 px-4 text-left">Nama Produk</th>
                    <th class="py-3 px-4 text-left">Stok Tercatat</th>
                    <th class="py-3 px-4 text-left">Stok Fisik</th>
                    <th class="py-3 px-4 text-left">Selisih</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach ($products as $index => $product)
                <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                    <td class="py-3 px-4">{{ $index + 1 }}</td>
                    <td class="py-3 px-4">{{ $product->name }}</td>
                    <td class="py-3 px-4" id="recorded_stock_{{ $product->id }}">{{ $product->stock }}</td>
                    <td class="py-3 px-4">
                        <input type="number" name="actual_stock" id="actual_stock_{{ $product->id }}" 
                            class="border p-1 w-full"
                            value="{{ $product->stock }}"
                            min="0"
                            oninput="calculateDifference({{ $product->id }})">
                    </td>
                    <td class="py-3 px-4" id="difference_{{ $product->id }}">0</td>
                    <td class="py-3 px-4 flex gap-2">
                        <form action="{{ route('stock_opname.update', $product->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="actual_stock" id="hidden_stock_{{ $product->id }}" value="{{ $product->stock }}">
                            <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">Update</button>
                        </form>
                        <form action="{{ route('stock_opname.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="actual_stock" id="hidden_audit_{{ $product->id }}" value="{{ $product->stock }}">
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

        document.getElementById(`difference_${productId}`).innerText = stockDifference;
        document.getElementById(`hidden_stock_${productId}`).value = actualStock; // Update hidden input
    }
</script>

@endsection
