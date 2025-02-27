@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 bg-[url('/img/box.jpg')] bg-cover bg-center py-8">
    <h1 class="text-3xl font-extrabold mb-6 text-white">Import / Export Produk</h1>
    
    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="bg-green-500 text-white p-4 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif
    
    @if(session('errorMessages'))
        <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
            <strong>Error detail:</strong>
            <ul class="list-disc pl-5 mt-2">
                @foreach(session('errorMessages') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Export Section --}}
        <div class="bg-gray-800 bg-opacity-70 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-white">Export Data Produk</h2>
            <p class="text-gray-300 mb-4">Download data produk dalam format Excel untuk analisis atau backup.</p>
            
            <a href="{{ route('products.export') }}" class="bg-blue-600 text-white py-2 px-6 rounded-lg inline-block hover:bg-blue-700 transition-all">
                <i class="fas fa-download mr-2"></i> Export Data Produk
            </a>
        </div>
        
        {{-- Import Section --}}
        <div class="bg-gray-800 bg-opacity-70 p-6 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4 text-white">Import Data Produk</h2>
            <p class="text-gray-300 mb-4">Upload file Excel untuk mengimpor produk baru secara massal.</p>
            
            <div class="mb-4">
                <a href="{{ route('products.export-template') }}" class="bg-green-600 text-white py-2 px-6 rounded-lg inline-block hover:bg-green-700 transition-all">
                    <i class="fas fa-file-excel mr-2"></i> Download Template
                </a>
            </div>
            
            <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="bg-gray-700 bg-opacity-50 p-4 rounded-lg">
                @csrf
                <div class="mb-4">
                    <label for="file" class="block text-white mb-2">Pilih File Excel</label>
                    <input type="file" name="file" id="file" accept=".xlsx,.xls" class="block w-full text-white">
                    @error('file')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <button type="submit" class="bg-purple-600 text-white py-2 px-6 rounded-lg hover:bg-purple-700 transition-all">
                    <i class="fas fa-upload mr-2"></i> Import Data
                </button>
            </form>
        </div>
    </div>
    
    <div class="mt-8">
        <a href="{{ route('products.index') }}" class="bg-gray-600 text-white py-2 px-6 rounded-lg inline-block hover:bg-gray-700 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Kembali ke Daftar Produk
        </a>
    </div>
</div>
@endsection