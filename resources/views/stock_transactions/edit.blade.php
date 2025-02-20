@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Update Transaksi Stok</h1>
    <form action="{{ route('stock_transactions.update', $transaction->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="product_id" class="block text-sm font-medium text-gray-700">Produk</label>
            <select name="product_id" id="product_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Produk</option>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" {{ $transaction->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="user_id" class="block text-sm font-medium text-gray-700">Pengguna</label>
            <select name="user_id" id="user_id" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Pengguna</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" {{ $transaction->user_id == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="type" class="block text-sm font-medium text-gray-700">Tipe Transaksi</label>
            <select name="type" id="type" class="mt1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="">Pilih Tipe</option>
                <option value="in" {{ $transaction->type == 'in' ? 'selected' : '' }}>Masuk</option>
                <option value="out" {{ $transaction->type == 'out' ? 'selected' : '' }}>Keluar</option>
            </select>
        </div>
        <div