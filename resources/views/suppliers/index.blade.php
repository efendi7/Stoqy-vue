@extends('layouts.app')

@section('content')
@if(session('success'))
    <div id="flash-success" class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-4 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md">
        <span>{{ session('success') }}</span>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
@endif

@if(session('error'))
    <div id="flash-error" class="max-w-lg mx-auto bg-red-600 text-white p-3 rounded-lg mb-4 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md">
        <span>{{ session('error') }}</span>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
@endif

<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Penyuplai</h1>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('suppliers.index') }}" class="mb-6 flex gap-4">
        <input type="text" name="search" id="search" placeholder="Cari berdasarkan nama atau kontak" class="border border-gray-300 rounded-lg py-2 px-4 w-full text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all">
        <button type="button" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all">Cari</button>
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

    {{-- Tombol Tambah Supplier hanya untuk admin --}}
    @if(auth()->user()->role !== 'warehouse_manager' && auth()->user()->role !== 'warehouse_staff')
        <div class="flex flex-wrap gap-3 mb-6">
            <a href="{{ route('suppliers.create') }}" class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition-all">Tambah Supplier</a>
        </div>
    @endif

    {{-- Tabel Supplier --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nama</th>
                    <th class="py-3 px-4 text-left">Kontak</th>
                    <th class="py-3 px-4 text-left">Alamat</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700" id="supplier-table-body">
                @foreach($suppliers as $supplier)
                    <tr class="supplier-row hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                        <td class="py-3 px-4 supplier-name">{{ $supplier->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4 supplier-contact">{{ $supplier->contact ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $supplier->address ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $supplier->email ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex gap-2">
                                {{-- Tampilkan hanya untuk Admin atau role lainnya yang bukan warehouse_manager dan warehouse_staff --}}
                                @if(auth()->user()->role !== 'warehouse_manager' && auth()->user()->role !== 'warehouse_staff')
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-all">Edit</a>
                                    <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus supplier {{ $supplier->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-gray-500">Tidak ada akses</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $suppliers->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    // Fungsi untuk pencarian langsung
    document.querySelector('#search').addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase(); // Ambil nilai input pencarian
        const rows = document.querySelectorAll('.supplier-row'); // Ambil semua baris supplier
        rows.forEach(row => {
            const nameCell = row.querySelector('.supplier-name');
            const contactCell = row.querySelector('.supplier-contact');

            const name = nameCell.textContent.toLowerCase();
            const contact = contactCell.textContent.toLowerCase();

            // Reset highlight sebelum pengecekan
            nameCell.innerHTML = nameCell.textContent; 
            contactCell.innerHTML = contactCell.textContent;

            // Cek apakah nama atau kontak sesuai dengan query pencarian
            if (name.includes(searchQuery) || contact.includes(searchQuery)) {
                row.style.display = ''; // Tampilkan baris yang sesuai
                // Soroti teks yang cocok dengan background kuning
                if (name.includes(searchQuery)) {
                    nameCell.innerHTML = name.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
                if (contact.includes(searchQuery)) {
                    contactCell.innerHTML = contact.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
            } else {
                row.style.display = 'none'; // Sembunyikan baris yang tidak cocok
            }
        });
    });
</script>

@endsection
