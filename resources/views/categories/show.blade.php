@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Detail Produk</h1>
    <div class="bg-white shadow-md rounded p-4">
        <h2 class="text-xl font-semibold">{{ $product->name }}</h2>
        <p><strong>SKU:</strong> {{ $product->sku }}</p>
        <p><strong>Kategori:</strong> {{ $product->category->name }}</p>
        <p><strong>Supplier:</strong> {{ $product->supplier->name }}</p>
        <p><strong>Harga Beli:</strong> {{ $product->purchase_price }}</p>
        <p><strong>Harga Jual:</strong> {{ $product->sale_price }}</p>
        <p><strong>Stok:</strong> {{ $product->stock }}</p>
        <h3 class="text-lg font-semibold mt-4">Atribut Tambahan:</h3>
        @if($product->attributes && $product->attributes->count())
            @foreach($product->attributes as $attribute)
                <p><strong>{{ $attribute->name }}:</strong> {{ $attribute->value }}</p>
            @endforeach
        @else
            <p>Tidak ada atribut tambahan.</p>
        @endif
        <a href="{{ route('products.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-md mt-4">Kembali</a>
    </div>
</div>
@endsection
