@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Atribut Produk</h1>
    <a href="{{ route('product_attributes.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded mb-4 inline-block">Tambah Atribut</a>
    <table class="min-w-full bg-white rounded-lg shadow overflow-hidden">
        <thead class="bg-gray-800 text-white">
            <tr>
                <th class="py-3 px-4 text-left">ID Produk</th>
                <th class="py-3 px-4 text-left">Nama Atribut</th>
                <th class="py-3 px-4 text-left">Nilai Atribut</th>
                <th class="py-3 px-4 text-center">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($productAttributes as $productAttribute)
                <tr class="border-b">
                    <td class="py-3 px-4">{{ $productAttribute->product_id }}</td>
                    <td class="py-3 px-4">{{ $productAttribute->attribute_name }}</td>
                    <td class="py-3 px-4">{{ $productAttribute->attribute_value }}</td>
                    <td class="py-3 px-4 text-center">
                        <div class="inline-flex gap-2">
                            <a href="{{ route('product_attributes.edit', $productAttribute->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded hover:bg-yellow-600">Edit</a>
                            <form action="{{ route('product_attributes.destroy', $productAttribute->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus atribut ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded hover:bg-red-600">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
