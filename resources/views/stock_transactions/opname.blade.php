@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Stock Opname</h1>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Produk</th>
                    <th class="py-3 px-4 text-left">Stok Aktual</th>
                    <th class="py-3 px-4 text-left">Stok Sistem</th>
                    <th class="py-3 px-4 text-left">Selisih</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $product->name }}</td>
                    <td class="py-3 px-4">
                        <input type="number" 
                               class="border rounded py-1 px-2 actual-stock" 
                               data-product-id="{{ $product->id }}"
                               value="{{ $product->stock }}">
                    </td>
                    <td class="py-3 px-4">{{ $product->stock }}</td>
                    <td class="py-3 px-4 difference" data-product-id="{{ $product->id }}">0</td>
                    <td class="py-3 px-4">
                        <button class="bg-blue-500 text-white py-1 px-4 rounded save-opname" 
                                data-product-id="{{ $product->id }}">
                            Simpan
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const actualStockInputs = document.querySelectorAll('.actual-stock');
    const saveButtons = document.querySelectorAll('.save-opname');

    // Calculate difference when actual stock changes
    actualStockInputs.forEach(input => {
        input.addEventListener('input', function() {
            const productId = this.dataset.productId;
            const systemStock = parseInt(this.closest('tr').querySelector('td:nth-child(3)').textContent);
            const actualStock = parseInt(this.value);
            const difference = actualStock - systemStock;
            
            document.querySelector(`.difference[data-product-id="${productId}"]`).textContent = difference;
        });
    });

    // Handle save button click
    saveButtons.forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const actualStock = document.querySelector(`.actual-stock[data-product-id="${productId}"]`).value;

            fetch('/stock_transactions', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    product_id: productId,
                    type: 'Opname',
                    quantity: actualStock
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Stock opname berhasil disimpan!');
                    window.location.reload();
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
});
</script>
@endsection
