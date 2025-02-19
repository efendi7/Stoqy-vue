@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Edit Produk</h1>
    <form action="{{ route('products.update', $product->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name', $product->name) }}" required>
        </div>
        <div class="mb-4">
            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
            <input type="text" name="sku" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="sku" value="{{ old('sku', $product->sku) }}" required>
        </div>
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ $product->supplier_id == $supplier->id ? 'selected' : '' }}>{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli</label>
            <input type="number" name="purchase_price" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required>
        </div>
        <div class="mb-4">
            <label for="sale_price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
            <input type="number" name="sale_price" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="sale_price" value="{{ old('sale_price', $product->sale_price) }}" required>
        </div>
        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="stock" value="{{ old('stock', $product->stock) }}" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Simpan</button>
    </form>
</div>
@endsection
