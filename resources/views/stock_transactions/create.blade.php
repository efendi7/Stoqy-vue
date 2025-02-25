@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Transaksi Stok</h1>
    <form action="{{ route('stock_transactions.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="product_id" class="block text-gray-700">Produk</label>
            <select name="product_id" id="product_id" class="form-select">
                @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700">User</label>
            <select name="user_id" id="user_id" class="form-select">
                @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="type" class="block text-gray-700">Jenis</label>
            <select name="type" id="type" class="form-select">
                <option value="Masuk">Masuk</option>
                <option value="Keluar">Keluar</option>
            </select>
        </div>
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700">Kuantitas</label>
            <input type="number" name="quantity" id="quantity" class="form-input" required>
        </div>
        <div class="mb-4">
            <label for="transaction_date" class="block text-gray-700">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" id="transaction_date" class="form-input">
        </div>
        <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
    </form>
</div>
@endsection
