@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Detail Atribut Produk</h1>
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-semibold">ID Produk: {{ $productAttribute->product_id }}</h2>
        <p><strong>Nama Atribut:</strong> {{ $productAttribute->attribute_name }}</p>
        <p><strong>Nilai Atribut:</strong> {{ $productAttribute->attribute_value }}</p>
        <a href="{{ route('product_attributes.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Kembali</a>
    </div>
</div>
@endsection
