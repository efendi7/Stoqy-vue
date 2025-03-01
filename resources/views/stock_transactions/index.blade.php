@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Transaksi Stok</h1>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-3 rounded-lg mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-500 text-white p-3 rounded-lg mb-4">
            {{ session('error') }}
        </div>
    @endif

    {{-- Form Stok Opname --}}
    <form action="{{ route('stock_opname.store') }}" method="POST" class="mb-4 bg-white p-4 shadow rounded-lg">
        @csrf
        <label for="product_id" class="block font-semibold">Pilih Produk:</label>
        <select name="product_id" required class="border rounded w-full py-2 px-3">
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>

        <label for="new_stock" class="block font-semibold mt-2">Stok Baru:</label>
        <input type="number" name="new_stock" min="0" required class="border rounded w-full py-2 px-3" value="{{ old('new_stock') }}">

        @error('new_stock')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
        @enderror

        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded mt-3">Simpan</button>
    </form>

    {{-- Tombol Stok Opname --}}
    <a href="{{ route('stock_opname.index') }}" class="bg-purple-500 text-white py-2 px-4 rounded mb-4 inline-block">
        Lakukan Stok Opname
    </a>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('stock_transactions.index') }}" class="mb-4 flex gap-2">
        <input type="text" name="search" placeholder="Cari berdasarkan produk atau jenis" 
               class="border rounded py-2 px-4 w-full" value="{{ request('search') }}">
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Cari</button>
    </form>

    {{-- Tombol Tambah Transaksi --}}
    <a href="{{ route('stock_transactions.create') }}" class="bg-green-500 text-white py-2 px-4 rounded mb-4 inline-block">
        Tambah Transaksi
    </a>

    {{-- Tabel Transaksi --}}
    <table class="w-full border-collapse border border-gray-300 mt-4">
        <thead class="bg-gray-200">
            <tr>
                <th class="border border-gray-300 px-4 py-2">Produk</th>
                <th class="border border-gray-300 px-4 py-2">User</th>
                <th class="border border-gray-300 px-4 py-2">Jenis</th>
                <th class="border border-gray-300 px-4 py-2">Kuantitas</th>
                <th class="border border-gray-300 px-4 py-2">Status</th>
                <th class="border border-gray-300 px-4 py-2">Tanggal Transaksi</th>
                <th class="border border-gray-300 px-4 py-2">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
            <tr class="text-center">
                <td class="border border-gray-300 px-4 py-2">{{ $transaction->product->name ?? 'Produk Tidak Ditemukan' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $transaction->user->name ?? 'User Tidak Ditemukan' }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $transaction->type }}</td>
                <td class="border border-gray-300 px-4 py-2">{{ $transaction->quantity }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <form action="{{ route('stock_transactions.updateStatus', $transaction->id) }}" method="POST" class="inline">
                        @csrf
                        <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                            <option value="Pending" @if($transaction->status == 'Pending') selected @endif>Pending</option>
                            <option value="Accepted" @if($transaction->status == 'Accepted') selected @endif>Accepted</option>
                            <option value="Rejected" @if($transaction->status == 'Rejected') selected @endif>Rejected</option>
                        </select>
                    </form>
                </td>
                <td class="border border-gray-300 px-4 py-2">{{ $transaction->transaction_date }}</td>
                <td class="border border-gray-300 px-4 py-2">
                    <a href="{{ route('stock_transactions.edit', $transaction->id) }}" class="bg-yellow-500 text-white px-3 py-1 rounded">Edit</a>
                    <form action="{{ route('stock_transactions.destroy', $transaction->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded" onclick="return confirm('Yakin ingin menghapus?')">Hapus</button>
                    </form>
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
@endsection
