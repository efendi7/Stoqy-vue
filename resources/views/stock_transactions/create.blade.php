@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Transaksi Stok Baru</h1>

    <!-- Tambahkan pesan error di sini -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('stock_transactions.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
            <select name="product_id" id="product_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Pengguna</label>
            <select name="user_id" id="user_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Pengguna</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
            <select name="type" id="type" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Tipe</option>
                <option value="Masuk">Masuk</option>
                <option value="Keluar">Keluar</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-sm font-medium text-gray-700">Jumlah</label>
            <input type="number" name="quantity" id="quantity" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
        </div>
        <!-- Tambahkan kolom tanggal transaksi di sini -->
        <div class="mb-4">
            <label for="transaction_date" class="block text-sm font-medium text-gray-700">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" id="transaction_date" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
