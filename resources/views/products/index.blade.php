@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Produk</h1>

    {{-- Flash Message Sukses --}}
    @if(session('success'))
    <div id="flash-success" class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
    @endif

    {{-- Flash Message Error --}}
    @if(session('error'))
    <div id="flash-error" class="max-w-lg mx-auto bg-red-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
    @endif

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('products.index') }}" class="mb-6 flex gap-4">
        <input type="text" name="search" placeholder="Cari berdasarkan nama, SKU, atau kategori" class="border border-gray-300 rounded-lg py-2 px-4 w-full text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" oninput="filterProducts()">
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
                    <th class="py-3 px-4 text-left">Gambar</th>
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
            <tbody class="divide-y divide-gray-700" id="product-table-body">
                @foreach($products as $product)
                <tr class="product-row hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                    <td class="py-3 px-4 product-name">{{ $product->name ?? 'N/A' }}</td>
                    <td class="text-center">
                        @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="h-8 w-8 object-cover mx-auto rounded">
                        @else
                        <img src="{{ asset('images/no-image.png') }}" alt="" class="h-8 w-8 object-cover mx-auto opacity-30">
                        @endif
                    </td>
                    <td class="py-3 px-4 product-sku">{{ $product->sku ?? 'N/A' }}</td>
                    <td class="py-3 px-4 product-category">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ $product->supplier->name ?? 'N/A' }}</td>
                    <td class="py-3 px-4">{{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">{{ number_format($product->sale_price, 0, ',', '.') }}</td>
                    <td class="py-3 px-4">{{ $product->stock ?? 0 }}</td>
                    <td class="py-3 px-4">{{ $product->minimum_stock ?? 0 }}</td>
                    <td class="py-3 px-4 text-center">
                        @php
                        $statusMap = [
                            'Habis' => 'border-red-500 font-semibold text-red-500',
                            'Warning' => 'border-yellow-500 font-semibold text-yellow-500',
                            'Tersedia' => 'border-green-500 font-semibold text-green-500',
                        ];

                        if ($product->stock == 0) {
                            $status = 'Habis';
                        } elseif ($product->stock < $product->minimum_stock) {
                            $status = 'Warning';
                        } else {
                            $status = 'Tersedia';
                        }
                        @endphp

                        <span class="px-3 py-1 rounded-lg border {{ $statusMap[$status] }}">
                            {{ $status }}
                        </span>
                    </td>

                    <td class="py-3 px-4 text-center">
                        <div class="relative inline-block text-left">
                            <button onclick="toggleDropdown({{ $product->id }})" class="bg-gray-500 text-white py-1 px-4 rounded-lg hover:bg-gray-600 focus:outline-none" id="menu-button-{{ $product->id }}" aria-expanded="false" aria-haspopup="true">
                                Aksi
                            </button>
                            <div class="hidden origin-top-right absolute right-0 bottom-0 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10" id="menu-items-{{ $product->id }}" role="menu" aria-orientation="vertical" aria-labelledby="menu-button-{{ $product->id }}">
                                <div class="py-1" role="none">
                                    <a href="{{ route('products.show', $product->id) }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">Detail</a>
                                    <a href="{{ route('products.edit', $product->id) }}" class="text-gray-700 block px-4 py-2 text-sm" role="menuitem">Edit</a>
                                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 block px-4 py-2 text-sm" role="menuitem">Hapus</button>
                                    </form>
                                </div>
                            </div>
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

<script>
    function filterProducts() {
        const searchQuery = document.querySelector('input[name="search"]').value.toLowerCase();
        const rows = document.querySelectorAll('.product-row');
        let found = false;

        rows.forEach(row => {
            const nameCell = row.querySelector('.product-name');
            const skuCell = row.querySelector('.product-sku');
            const categoryCell = row.querySelector('.product-category');

            const name = nameCell.textContent.toLowerCase();
            const sku = skuCell.textContent.toLowerCase();
            const category = categoryCell.textContent.toLowerCase();

            nameCell.innerHTML = nameCell.textContent;
            skuCell.innerHTML = skuCell.textContent;
            categoryCell.innerHTML = categoryCell.textContent;

            if (name.includes(searchQuery) || sku.includes(searchQuery) || category.includes(searchQuery)) {
                row.style.display = '';
                found = true;

                if (name.includes(searchQuery)) {
                    nameCell.innerHTML = name.replace(new RegExp(searchQuery, 'gi'), match => `<mark>${match}</mark>`);
                }
                if (sku.includes(searchQuery)) {
                    skuCell.innerHTML = sku.replace(new RegExp(searchQuery, 'gi'), match => `<mark>${match}</mark>`);
                }
                if (category.includes(searchQuery)) {
                    categoryCell.innerHTML = category.replace(new RegExp(searchQuery, 'gi'), match => `<mark>${match}</mark>`);
                }
            } else {
                row.style.display = 'none';
            }
        });

        const noResultsMessage = document.querySelector('#no-results-message');
        if (!found) {
            noResultsMessage.style.display = 'block';
        } else {
            noResultsMessage.style.display = 'none';
        }
    }
</script>
@endsection
