@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold mb-4">Stock Opname</h2>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">No</th>
                    <th class="border p-2">Nama Produk</th>
                    <th class="border p-2">Stok Tercatat</th>
                    <th class="border p-2">Stok Fisik</th>
                    <th class="border p-2">Selisih</th>
                    <th class="border p-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $index => $product)
                    <tr>
                        <td class="border p-2">{{ $index + 1 }}</td>
                        <td class="border p-2">{{ $product->name }}</td>
                        <td class="border p-2" id="recorded_stock_{{ $product->id }}">{{ $product->stock }}</td>
                        <td class="border p-2">
                            <input type="number" name="actual_stock" id="actual_stock_{{ $product->id }}" 
                                class="border p-1 w-full"
                                value="{{ $product->stock }}"
                                min="0"
                                oninput="calculateDifference({{ $product->id }})">
                        </td>
                        <td class="border p-2" id="difference_{{ $product->id }}">0</td>
                        <td class="border p-2">
    <form action="{{ route('stock_opname.update', $product->id) }}" method="POST" class="inline">
        @csrf
        @method('PUT')
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="actual_stock" id="hidden_stock_{{ $product->id }}" value="{{ $product->stock }}">
        
        <button type="submit" class="bg-blue-500 text-white px-3 py-1 rounded">
            Update
        </button>
    </form>

    <form action="{{ route('stock_opname.store') }}" method="POST" class="inline">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="actual_stock" id="hidden_audit_{{ $product->id }}" value="{{ $product->stock }}">

        <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded">
            Save Audit
        </button>
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
