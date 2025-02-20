@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Detail Produk</h1>
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-200 p-4">
            <h2 class="text-2xl font-semibold text-gray-900">{{ $product->name }}</h2>
        </div>
        <div class="p-6">
            <p class="text-lg"><strong>SKU:</strong> {{ $product->sku }}</p>
            <p class="text-lg"><strong>Kategori:</strong> {{ $product->category->name }}</p>
            <p class="text-lg"><strong>Supplier:</strong> {{ $product->supplier->name }}</p>
            <p class="text-lg"><strong>Harga Beli:</strong> Rp {{ number_format($product->purchase_price, 2, ',', '.') }}</p>
            <p class="text-lg"><strong>Harga Jual:</strong> Rp {{ number_format($product->sale_price, 2, ',', '.') }}</p>
            <p class="text-lg"><strong>Stok:</strong> {{ $product->stock }}</p>
            <h3 class="text-xl font-semibold mt-6 text-gray-800">Atribut Tambahan:</h3>
            @if($product->attributes && $product->attributes->count())
                @foreach($product->attributes as $attribute)
                    <p class="text-lg"><strong>{{ $attribute->attribute_name }}:</strong> {{ $attribute->attribute_value }}</p>
                @endforeach
            @else
                <p class="text-lg">Tidak ada atribut tambahan.</p>
            @endif
            <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-md mt-6 hover:bg-blue-600">Kembali</a>
        </div>
    </div>
</div>
@endsection
