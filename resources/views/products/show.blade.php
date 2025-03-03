@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">Detail Produk</h1>
    
    <div class="bg-white shadow-lg rounded-lg overflow-hidden p-6 md:flex md:items-start md:space-x-6">
        <!-- Bagian Kiri: Detail Produk -->
        <div class="md:w-2/3">
            <h2 class="text-2xl font-semibold text-gray-900">{{ $product->name ?? 'Tidak ada nama' }}</h2>
            <p class="text-lg"><strong>SKU:</strong> {{ $product->sku ?? 'Tidak tersedia' }}</p>
            <p class="text-lg"><strong>Kategori:</strong> {{ $product->category->name ?? 'Tidak ada kategori' }}</p>
            <p class="text-lg"><strong>Supplier:</strong> {{ $product->supplier->name ?? 'Tidak ada supplier' }}</p>
            <p class="text-lg"><strong>Harga Beli:</strong> Rp {{ number_format($product->purchase_price ?? 0, 2, ',', '.') }}</p>
            <p class="text-lg"><strong>Harga Jual:</strong> Rp {{ number_format($product->sale_price ?? 0, 2, ',', '.') }}</p>
            <p class="text-lg"><strong>Stok:</strong> {{ $product->stock ?? 0 }}</p>

            <h3 class="text-xl font-semibold mt-6 text-gray-800">Atribut Tambahan:</h3>
            @if($product->attributes && $product->attributes->count())
                @foreach($product->attributes as $attribute)
                    <p class="text-lg"><strong>{{ $attribute->attribute_name ?? 'Tidak ada atribut' }}:</strong> {{ $attribute->attribute_value ?? 'Tidak ada nilai' }}</p>
                @endforeach
            @else
                <p class="text-lg">Tidak ada atribut tambahan.</p>
            @endif
            
            <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white px-6 py-2 rounded-md mt-6 hover:bg-blue-600">Kembali</a>
        </div>

        <!-- Bagian Kanan: Gambar Produk -->
        @if($product->image)
        <div class="md:w-1/3 flex justify-center">
            <img src="{{ asset('storage/' . $product->image) }}" alt="Gambar Produk" class="w-64 h-64 object-cover rounded-lg shadow-md">
        </div>
        @endif
    </div>
</div>
@endsection
