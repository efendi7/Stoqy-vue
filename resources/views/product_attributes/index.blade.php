@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Atribut Produk</h1>

    {{-- Notifikasi Kesalahan --}}
    @if ($errors->any())
        <div class="bg-red-600 border border-red-400 text-white px-4 py-3 rounded-lg relative mb-6" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Tombol Tambah --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('product_attributes.create') }}" class="bg-green-500 text-white py-2 px-6 rounded-lg hover:bg-green-600 transition-all">Tambah Atribut</a>
    </div>

    {{-- Tabel Atribut Produk --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">ID Produk</th>
                    <th class="py-3 px-4 text-left">Nama Atribut</th>
                    <th class="py-3 px-4 text-left">Nilai Atribut</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-700">
                @foreach($productAttributes as $productAttribute)
                    <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                        <td class="py-3 px-4">{{ $productAttribute->product_id }}</td>
                        <td class="py-3 px-4">{{ $productAttribute->attribute_name }}</td>
                        <td class="py-3 px-4">{{ $productAttribute->attribute_value }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('product_attributes.edit', $productAttribute->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-all">Edit</a>
                                <form action="{{ route('product_attributes.destroy', $productAttribute->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus atribut ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $productAttributes->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
