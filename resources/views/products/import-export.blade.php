@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gray-800 text-white px-6 py-4 text-lg font-semibold">Import/Export Produk</div>

            <div class="p-6">
                @if (session('success'))
                    <div class="mb-4 p-3 bg-green-100 text-green-700 border border-green-400 rounded-lg">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded-lg">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded-lg">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (session('errorMessages'))
                    <div class="mb-4 p-3 bg-red-100 text-red-700 border border-red-400 rounded-lg">
                        <strong>Error saat mengimpor:</strong>
                        <ul class="list-disc pl-5">
                            @foreach (session('errorMessages') as $errorMessage)
                                <li>{{ $errorMessage }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-6">
                    <h5 class="text-lg font-semibold mb-2">Import Produk</h5>
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data" class="space-y-2">
                        @csrf
                        <div class="flex items-center gap-2">
                            <input type="file" name="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" required>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Import</button>
                        </div>
                        <small class="text-gray-500">File Excel harus dalam format .xlsx atau .xls (max 10MB)</small>
                    </form>
                </div>

                <div class="mb-6">
                    <h5 class="text-lg font-semibold mb-2">Download Template</h5>
                    <a href="{{ route('products.export-template') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg">Download Template Import</a>
                    <small class="block text-gray-500 mt-1">Download template kosong untuk diisi data produk yang akan diimport</small>
                </div>

                <div>
                    <h5 class="text-lg font-semibold mb-2">Export Produk</h5>
                    <a href="{{ route('products.export') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">Export Semua Produk</a>
                    <small class="block text-gray-500 mt-1">Download semua data produk dalam format Excel</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection