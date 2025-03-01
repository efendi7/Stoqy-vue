@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Produk</h1>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex gap-4">
        <input type="text" name="search" placeholder="Cari berdasarkan nama, SKU, atau kategori" class="border border-gray-300 rounded-lg py-2 px-4 w-full text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all">Cari</button>
    </form>

    {{-- Notifikasi Kesalahan --}}
    @if ($errors->any())
        <div class="bg-red-600 border border-red-400 text-white px-4 py-3 rounded-lg relative mb-6" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Tambah, Lihat Kategori, Lihat Atribut, Import, dan Export --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('products.create') }}" class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition-all">Tambah Produk</a>
        <a href="{{ route('categories.index') }}" class="bg-purple-500 text-white py-2 px-6 rounded-lg hover:bg-purple-600 transition-all">Lihat Kategori Produk</a>
        <a href="{{ route('product_attributes.index') }}" class="bg-purple-500 text-white py-2 px-6 rounded-lg hover:bg-purple-600 transition-all">Lihat Atribut Produk</a>
        <a href="{{ route('products.import-export.index') }}" class="bg-blue-500 text-white py-2 px-6 rounded-lg hover:bg-blue-600 transition-all">Import/Export</a>
    </div>

    {{-- Tabel Produk --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">SKU</th>
                    <th class="py-3 px-4 text-left">Kategori</th>
                    <th class="py-3 px-4 text-left">Supplier</th>
                    <th class="py-3 px-4 text-left">Harga Beli</th>
                    <th class="py-3 px-4 text-left">Harga Jual</th>
                    <th class="py-3 px-4 text-left">Stok</th>
                    <th class="py-3 px-4 text-left">Stok Minimum</th>
                    <th class="py-3 px-4 text-center">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($products as $product)
                    <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                        <td class="py-3 px-4">{{ $product->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $product->sku ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $product->category->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $product->supplier->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ number_format($product->sale_price, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">{{ $product->stock ?? 0 }}</td>
                        <td class="py-3 px-4">{{ $product->minimum_stock ?? 0 }}</td>
                        <td class="py-3 px-4 text-center">
                            @php
                                $status = '';
                                $statusClass = '';
                                if ($product->stock == 0) {
                                    $status = 'Habis';
                                    $statusClass = 'font-bold text-red-500';
                                } elseif ($product->stock < $product->minimum_stock) {
                                    $status = 'Warning';
                                    $statusClass = 'font-bold text-yellow-500';
                                } else {
                                    $status = 'Tersedia';
                                    $statusClass = 'font-bold text-green-500';
                                }
                            @endphp
                            <span class="{{ $statusClass }} px-2 py-1 rounded">{{ $status }}</span>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('products.show', $product->id) }}" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 transition-all">Detail</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-all">Edit</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $products->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
