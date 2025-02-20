@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Transaksi Stok</h1>

    <!-- Menampilkan pesan kesalahan validasi -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('stock_transactions.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded mb-4 inline-block">Tambah Transaksi</a>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID</th>
                    <th class="py-3 px-4 text-left">Produk</th>
                    <th class="py-3 px-4 text-left">Pengguna</th>
                    <th class="py-3 px-4 text-left">Jenis</th>
                    <th class="py-3 px-4 text-left">Jumlah</th>
                    <th class="py-3 px-4 text-left">Tanggal</th>
                    <th class="py-3 px-4 text-left">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $transaction)
                    <tr class="border-b">
                        <td class="py-3 px-4">{{ $transaction->id }}</td>
                        <td class="py-3 px-4">{{ $transaction->product->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $transaction->user->name ?? 'N/A' }}</td>
                        <td class="py-3 px-4">{{ $transaction->type == 'in' ? 'Masuk' : 'Keluar' }}</td>
                        <td class="py-3 px-4">{{ $transaction->quantity }}</td>
                        <td class="py-3 px-4">{{ $transaction->transaction_date }}</td>
                        <td class="py-3 px-4">{{ $transaction->status ?? 'N/A' }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('stock_transactions.edit', $transaction->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded hover:bg-yellow-600">Edit</a>
                                <form action="{{ route('stock_transactions.destroy', $transaction->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus transaksi ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
