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
                                oninput="calculateDifference({{ $product->id }})">
                        </td>
                        <td class="border p-2" id="difference_{{ $product->id }}">0</td>
                        <td class="border p-2">
                            <button onclick="updateStock({{ $product->id }})" 
                                class="bg-blue-500 text-white px-3 py-1 rounded">
                                Update
                            </button>
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
    }

    function updateStock(productId) {
        let actualStock = document.getElementById(`actual_stock_${productId}`).value;
        let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/stock_opname/${productId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ actual_stock: actualStock })
        }).then(response => response.json())
        .then(data => {
            alert(data.message);
            location.reload(); // Refresh halaman setelah update sukses
        })
        .catch(error => console.error('Error:', error));
    }
</script>
@endsection
