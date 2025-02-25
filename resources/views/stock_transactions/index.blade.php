@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Transaksi Stok</h1>
    <form method="GET" action="{{ route('stock_transactions.index') }}" class="mb-4">
        <input type="text" name="search" placeholder="Cari berdasarkan produk atau jenis" class="border rounded py-2 px-4">
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Cari</button>
    </form>
    <a href="{{ route('stock_transactions.create') }}" class="btn btn-primary">Tambah Transaksi</a>
    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>User</th>
                <th>Jenis</th>
                <th>Kuantitas</th>
                <th>Tanggal Transaksi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->product->name }}</td>
                <td>{{ $transaction->user->name }}</td>
                <td>{{ $transaction->type }}</td>
                <td>{{ $transaction->quantity }}</td>
                <td>{{ $transaction->transaction_date }}</td>
                <td>
                    <a href="{{ route('stock_transactions.edit', $transaction->id) }}" class="btn btn-warning">Edit</a>
                    <form action="{{ route('stock_transactions.destroy', $transaction->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
