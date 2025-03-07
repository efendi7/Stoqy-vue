@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center">
    <div class="bg-white bg-opacity-70 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">
        <h1 class="text-xl font-bold text-gray-800 text-center mb-6 pt-16">Tambah Transaksi Stok</h1>

        {{-- Flash Message --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Form Tambah Transaksi --}}
        <form action="{{ route('stock_transactions.store') }}" method="POST" class="space-y-4">
            @csrf
            
            {{-- Produk --}}
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-800">Produk</label>
                <select name="product_id" id="product_id" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
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
            <div>
                <label for="user_id" class="block text-sm font-medium text-gray-800">User</label>
                <select name="user_id" id="user_id" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
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
            <div>
                <label for="type" class="block text-sm font-medium text-gray-800">Jenis</label>
                <select name="type" id="type" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
                    <option value="Masuk" {{ old('type') == 'Masuk' ? 'selected' : '' }}>Masuk</option>
                    <option value="Keluar" {{ old('type') == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                </select>
                @error('type')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Kuantitas --}}
            <div>
                <label for="quantity" class="block text-sm font-medium text-gray-800">Kuantitas</label>
                <input type="number" name="quantity" id="quantity" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" value="{{ old('quantity') }}" required>
                @error('quantity')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tanggal Transaksi --}}
            <div>
                <label for="transaction_date" class="block text-sm font-medium text-gray-800">Tanggal Transaksi</label>
                <input type="date" name="transaction_date" id="transaction_date" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all" value="{{ old('transaction_date') }}" required>
                @error('transaction_date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan --}}
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-800">Catatan</label>
                <textarea name="notes" id="notes" class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Simpan --}}
            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">
                Simpan Transaksi
            </button>
        </form>

        {{-- Tombol Kembali --}}
        <div class="mt-4 text-center">
            <a href="{{ route('stock_transactions.index') }}" class="text-blue-500 hover:underline">Kembali ke Daftar Transaksi</a>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-fadeIn {
            animation: fadeIn 0.8s ease-out;
        }
    </style>
</div>
@endsection
