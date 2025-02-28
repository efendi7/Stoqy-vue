@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Stok Barang</h1>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border p-2">Produk</th>
                    <th class="border p-2">Stok</th>
                    <th class="border p-2">Kategori</th>
                </tr>
            </thead>
            <tbody>
    @foreach($stok as $item)
    <tr class="border text-gray-800">
        <td class="border p-2">{{ $item->name }}</td>
        <td class="border p-2 text-center">{{ $item->stock }}</td>
        <td class="border p-2">{{ $item->category ? $item->category->name : 'No Category' }}</td>
    </tr>
    @endforeach
</tbody>

        </table>
    </div>
</div>
@endsection
