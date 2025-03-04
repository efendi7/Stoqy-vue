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
    <input 
        type="text" 
        id="search" 
        name="search" 
        placeholder="Cari berdasarkan nama, SKU, atau kategori" 
        class="w-full border border-gray-300 rounded-lg py-2 px-4 text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
    >
    <button 
        type="submit" 
        class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all"
    >
        Cari
    </button>
</form>



    <script>
        // Flash message otomatis hilang setelah 5 detik
        setTimeout(function() {
            const successMessage = document.getElementById('flash-success');
            const errorMessage = document.getElementById('flash-error');

            if (successMessage) {
                successMessage.remove();
            }
            if (errorMessage) {
                errorMessage.remove();
            }
        }, 5000); // 5000 ms = 5 detik

        document.addEventListener("DOMContentLoaded", function () {
    // Fungsi untuk toggle dropdown dan menangani posisi
    function toggleDropdown(event, dropdown) {
        event.stopPropagation(); // Mencegah event bubbling agar dropdown tidak langsung tertutup

        // Tutup semua dropdown lain sebelum membuka yang baru
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (menu !== dropdown) {
                menu.classList.add('hidden');
            }
        });

        // Toggle class hidden
        dropdown.classList.toggle('hidden');

        // Cek apakah dropdown melebihi batas bawah layar
        let rect = dropdown.getBoundingClientRect();
        let windowHeight = window.innerHeight;

        if (rect.bottom > windowHeight) {
            dropdown.classList.add("top-auto", "bottom-full", "mb-2");
            dropdown.classList.remove("mt-2");
        } else {
            dropdown.classList.remove("top-auto", "bottom-full", "mb-2");
            dropdown.classList.add("mt-2");
        }
    }

    // Tambahkan event listener ke semua tombol dropdown
    document.querySelectorAll(".btn-dropdown, .dropdown-button").forEach(button => {
        button.addEventListener("click", function (event) {
            let dropdown = this.nextElementSibling; // Ambil elemen dropdown terkait

            if (dropdown) {
                toggleDropdown(event, dropdown);
            }
        });
    });

    // Event listener global untuk menutup dropdown jika klik di luar
    document.addEventListener("click", function (event) {
        document.querySelectorAll('.dropdown-menu').forEach(menu => {
            if (!menu.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    });
});

    </script>

<script>
    // Fungsi untuk pencarian langsung
    document.querySelector('#search').addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase(); // Ambil nilai input pencarian
        const rows = document.querySelectorAll('.product-row'); // Ambil semua baris produk
        rows.forEach(row => {
            const nameCell = row.querySelector('.product-name');
            const skuCell = row.querySelector('.product-sku');
            const categoryCell = row.querySelector('.product-category');

            const name = nameCell.textContent.toLowerCase();
            const sku = skuCell.textContent.toLowerCase();
            const category = categoryCell.textContent.toLowerCase();

            // Reset highlight sebelum pengecekan
            nameCell.innerHTML = nameCell.textContent; 
            skuCell.innerHTML = skuCell.textContent;
            categoryCell.innerHTML = categoryCell.textContent;

            // Cek apakah nama, SKU atau kategori sesuai dengan query pencarian
            if (name.includes(searchQuery) || sku.includes(searchQuery) || category.includes(searchQuery)) {
                row.style.display = ''; // Tampilkan baris yang sesuai
                // Soroti teks yang cocok dengan background kuning
                if (name.includes(searchQuery)) {
                    nameCell.innerHTML = name.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
                if (sku.includes(searchQuery)) {
                    skuCell.innerHTML = sku.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
                if (category.includes(searchQuery)) {
                    categoryCell.innerHTML = category.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
            } else {
                row.style.display = 'none'; // Sembunyikan baris yang tidak cocok
            }
        });
    });
</script>


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
                    @if(auth()->user()->role === 'warehouse_manager')
    <!-- Tombol Detail untuk warehouse_manager -->
    <a href="{{ route('products.show', $product->id) }}" class="bg-blue-500 text-white py-1 px-4 rounded-lg hover:bg-blue-600 transition-all">
        Detail
    </a>
@elseif(auth()->user()->role === 'admin')
    <!-- Dropdown untuk Admin -->
    <div class="relative inline-block text-left">
        <button data-product-id="{{ $product->id }}" class="btn-dropdown bg-gray-500 text-white py-1 px-4 rounded-lg hover:bg-gray-600 focus:outline-none">
    Aksi
</button>
<div id="menu-items-{{ $product->id }}" class="hidden dropdown-menu origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none z-10">
    <div class="py-1">
        <a href="{{ route('products.show', $product->id) }}" class="text-gray-700 block px-4 py-2 text-sm">Detail</a>
        <a href="{{ route('products.edit', $product->id) }}" class="text-gray-700 block px-4 py-2 text-sm">Edit</a>
        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
            @csrf
            @method('DELETE')
            <button type="submit" class="text-red-600 block px-4 py-2 text-sm w-full text-left">Hapus</button>
        </form>
    </div>
</div>

    </div>
@endif
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
