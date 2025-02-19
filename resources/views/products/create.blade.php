@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Produk Baru</h1>
    <form action="{{ route('products.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Produk</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label for="sku" class="block text-sm font-medium text-gray-700">SKU</label>
            <input type="text" name="sku" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="sku" value="{{ old('sku') }}" required>
        </div>
        <div class="mb-4">
            <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
            <select name="category_id" id="category_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Kategori</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="supplier_id" class="block text-sm font-medium text-gray-700">Supplier</label>
            <select name="supplier_id" id="supplier_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2">
                <option value="">Pilih Supplier</option>
                @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="purchase_price" class="block text-sm font-medium text-gray-700">Harga Beli</label>
            <input type="number" name="purchase_price" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="purchase_price" value="{{ old('purchase_price') }}" required>
        </div>
        <div class="mb-4">
            <label for="sale_price" class="block text-sm font-medium text-gray-700">Harga Jual</label>
            <input type="number" name="sale_price" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="sale_price" value="{{ old('sale_price') }}" required>
        </div>
        <div class="mb-4">
            <label for="stock" class="block text-sm font-medium text-gray-700">Stok</label>
            <input type="number" name="stock" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="stock" value="{{ old('stock') }}" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
