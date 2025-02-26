@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Transaksi Stok</h1>

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

    {{-- Form Tambah Transaksi --}}
    <form action="{{ route('stock_transactions.store') }}" method="POST" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        @csrf
        
        {{-- Produk --}}
        <div class="mb-4">
            <label for="product_id" class="block text-gray-700 font-bold mb-2">Produk</label>
            <select name="product_id" id="product_id" class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                @foreach($products as $product)
                <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                    {{ $product->name }}
                </option>
                @endforeach
            </select>
            @error('product_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- User --}}
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700 font-bold mb-2">User</label>
            <select name="user_id" id="user_id" class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                @foreach($users as $user)
                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                    {{ $user->name }}
                </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Jenis Transaksi --}}
        <div class="mb-4">
            <label for="type" class="block text-gray-700 font-bold mb-2">Jenis</label>
            <select name="type" id="type" class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200">
                <option value="Masuk" {{ old('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                <option value="Keluar" {{ old('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
            </select>
            @error('type')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Kuantitas --}}
        <div class="mb-4">
            <label for="quantity" class="block text-gray-700 font-bold mb-2">Kuantitas</label>
            <input type="number" name="quantity" id="quantity" class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200" value="{{ old('quantity') }}" required>
            @error('quantity')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tanggal Transaksi --}}
        <div class="mb-4">
            <label for="transaction_date" class="block text-gray-700 font-bold mb-2">Tanggal Transaksi</label>
            <input type="date" name="transaction_date" id="transaction_date" class="w-full px-3 py-2 border rounded shadow-sm focus:outline-none focus:ring focus:ring-blue-200" value="{{ old('transaction_date') }}" required>
            @error('transaction_date')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="mb-4">
            <button type="submit" class="w-full bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                Simpan Transaksi
            </button>
        </div>
    </form>

    {{-- Tombol Kembali --}}
    <div class="mb-4">
        <a href="{{ route('stock_transactions.index') }}" class="text-blue-500 hover:underline">Kembali ke Daftar Transaksi</a>
    </div>
</div>
@endsection
