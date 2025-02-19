@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Edit Atribut Produk</h1>
    <form action="{{ route('product_attributes.update', $productAttribute->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="product_id" class="block text-sm font-medium text-gray-700">ID Produk</label>
            <input type="text" name="product_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="product_id" value="{{ old('product_id', $productAttribute->product_id) }}" required>
        </div>
        <div class="mb-4">
            <label for="attribute_name" class="block text-sm font-medium text-gray-700">Nama Atribut</label>
            <input type="text" name="attribute_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="attribute_name" value="{{ old('attribute_name', $productAttribute->attribute_name) }}" required>
        </div>
        <div class="mb-4">
            <label for="attribute_value" class="block text-sm font-medium text-gray-700">Nilai Atribut</label>
            <input type="text" name="attribute_value" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="attribute_value" value="{{ old('attribute_value', $productAttribute->attribute_value) }}" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
