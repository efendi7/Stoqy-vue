@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Transaksi Stok</h1>
    
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

    <script>
        setTimeout(() => {
            let flashSuccess = document.getElementById('flash-success');
            if (flashSuccess) {
                flashSuccess.style.opacity = '0';
                setTimeout(() => flashSuccess.remove(), 500);
            }
        }, 4000);
    </script>
    @endif

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('stock_transactions.index') }}" class="mb-6 flex gap-4">
        <input type="text" id="search" name="search" placeholder="Cari berdasarkan produk atau jenis" 
               class="border border-gray-300 rounded-lg py-2 px-4 w-full text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all" 
               value="{{ request('search') }}">
        <button type="submit" class="bg-blue-600 text-white py-2 px-6 rounded-lg hover:bg-blue-700 transition-all">Cari</button>
    </form>

    <div class="flex justify-between items-center mb-6">
        {{-- Tombol Tambah Transaksi hanya untuk Warehouse Staff & Warehouse Manager --}}
        @if(in_array(auth()->user()->role, ['warehouse_staff', 'warehouse_manager']))
            <a href="{{ route('stock_transactions.create') }}" 
            class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition-all">
                Tambah Transaksi
            </a>
        @endif
    </div>

    {{-- Tabel Transaksi --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Produk</th>
                    <th class="py-3 px-4 text-left">User</th>
                    <th class="py-3 px-4 text-left">Jenis</th>
                    <th class="py-3 px-4 text-left">Kuantitas</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-left">Tanggal Transaksi</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @forelse($transactions as $transaction)
                <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all product-row">
                    <td class="py-3 px-4 product-name">{{ $transaction->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                    <td class="py-3 px-4 product-user">{{ $transaction->user->name ?? 'User Tidak Ditemukan' }}</td>
                    <td class="py-3 px-4 product-category">
                        <span class="px-2 py-1 rounded {{ $transaction->type == 'Masuk' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $transaction->type }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $transaction->quantity }}</td>
                    <td class="py-3 px-4">
                        @if($userRole === 'warehouse_manager')
                            <form action="{{ route('stock_transactions.update-status', $transaction->id) }}" method="POST" class="inline">
                                @csrf
                                <select name="status" onchange="this.form.submit()" 
                                class="border border-gray-300 rounded-lg px-2 py-1 text-black bg-white bg-opacity-50 focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
                                style="
                                    @if($transaction->status == 'Pending') background-color: #fef3c7; color: #b45309; @endif
                                    @if($transaction->status == 'Diterima') background-color: #d1fae5; color: #065f46; @endif
                                    @if($transaction->status == 'Ditolak') background-color: #fee2e2; color: #991b1b; @endif
                                ">
                                    <option value="Pending" @if($transaction->status == 'Pending') selected @endif>Pending</option>
                                    <option value="Diterima" @if($transaction->status == 'Diterima') selected @endif>Diterima</option>
                                    <option value="Ditolak" @if($transaction->status == 'Ditolak') selected @endif>Ditolak</option>
                                </select>
                            </form>
                        @else
                            <span class="px-2 py-1 rounded
                                {{ $transaction->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($transaction->status == 'Diterima' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800') }}">
                                {{ $transaction->status }}
                            </span>
                        @endif
                    </td>
                    <td class="py-3 px-4">{{ \Carbon\Carbon::parse($transaction->transaction_date)->format('d-m-Y') }}</td>
                    <td class="py-3 px-4">
                        @if($userRole === 'warehouse_manager' || $userRole === 'admin')
                            <a href="{{ route('stock_transactions.edit', $transaction->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-all mb-1 inline-block">Edit</a>
                            <form action="{{ route('stock_transactions.destroy', $transaction->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">Hapus</button>
                            </form>
                        @else
                            <span class="text-gray-500">Tidak ada akses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-4">Tidak ada transaksi stok.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $transactions->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    // Fungsi untuk pencarian langsung
    document.querySelector('#search').addEventListener('input', function() {
        const searchQuery = this.value.toLowerCase(); // Ambil nilai input pencarian
        const rows = document.querySelectorAll('.product-row'); // Ambil semua baris produk
        rows.forEach(row => {
            const nameCell = row.querySelector('.product-name'); // Nama Produk
            const userCell = row.querySelector('.product-user'); // Nama User
            const categoryCell = row.querySelector('.product-category'); // Jenis Transaksi

            const name = nameCell ? nameCell.textContent.toLowerCase() : '';
            const user = userCell ? userCell.textContent.toLowerCase() : '';
            const category = categoryCell ? categoryCell.textContent.toLowerCase() : '';

            // Reset highlight sebelum pengecekan
            nameCell.innerHTML = nameCell.textContent; 
            userCell.innerHTML = userCell.textContent;
            categoryCell.innerHTML = categoryCell.textContent;

            // Cek apakah nama produk, user, atau jenis sesuai dengan query pencarian
            if (name.includes(searchQuery) || user.includes(searchQuery) || category.includes(searchQuery)) {
                row.style.display = ''; // Tampilkan baris yang sesuai
                // Soroti teks yang cocok dengan background kuning
                if (name.includes(searchQuery)) {
                    nameCell.innerHTML = name.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
                }
                if (user.includes(searchQuery)) {
                    userCell.innerHTML = user.replace(new RegExp(searchQuery, 'gi'), match => `<mark class="bg-yellow-300">${match}</mark>`);
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

@endsection
