@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Import/Export Produk</h1>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg mb-6">
            <strong>Error saat mengimpor:</strong>
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form Import Produk --}}
    <div class="mb-6 bg-white bg-opacity-50 p-6 rounded-lg shadow-lg">
        <h5 class="text-lg font-semibold mb-2">Import Produk</h5>
        <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
            @csrf
            <div class="flex items-center gap-2">
                <input type="file" name="file" class="block w-full text-sm border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Import</button>
            </div>
            <small class="text-gray-500">File harus dalam format .csv (max 10MB)</small>

        </form>
    </div>

    {{-- Download Template --}}
    <div class="mb-6 bg-white bg-opacity-50 p-6 rounded-lg shadow-lg">
        <h5 class="text-lg font-semibold mb-2">Download Template</h5>
        <a href="{{ route('products.export-template') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Download Template Import</a>
        <small class="block text-gray-500 mt-1">Download template kosong untuk diisi data produk yang akan diimport</small>
    </div>

    {{-- Export Produk --}}
    <div class="bg-white bg-opacity-50 p-6 rounded-lg shadow-lg">
        <h5 class="text-lg font-semibold mb-2">Export Produk</h5>
        <a href="{{ route('products.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Export Semua Produk</a>
        <small class="block text-gray-500 mt-1">Download semua data produk dalam format .csv</small>
    </div>
</div>
@endsection
