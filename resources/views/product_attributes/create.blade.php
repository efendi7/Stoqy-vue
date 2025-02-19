@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Atribut Produk</h1>
    <form action="{{ route('product_attributes.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block text-sm font-medium text-gray-700">Pilih Produk</label>
            <select name="product_id" id="product_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Produk</option>
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="attribute_name" class="block text-sm font-medium text-gray-700">Nama Atribut</label>
            <input type="text" name="attribute_name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="attribute_name" value="{{ old('attribute_name') }}" required>
        </div>
        <div class="mb-4">
            <label for="attribute_value" class="block text-sm font-medium text-gray-700">Nilai Atribut</label>
            <input type="text" name="attribute_value" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="attribute_value" value="{{ old('attribute_value') }}" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
