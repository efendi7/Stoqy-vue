@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Daftar Kategori</h1>

    <!-- Menampilkan pesan kesalahan validasi -->
    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('categories.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded">Tambah Kategori</a>
    <table class="min-w-full leading-normal mt-4">
        <thead>
            <tr>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Deskripsi</th>
                <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categories as $category)
                <tr>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $category->name ?? 'N/A' }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">{{ $category->description ?? 'N/A' }}</td>
                    <td class="px-5 py-5 border-b border-gray-200 bg-white text-sm">
                        <a href="{{ route('categories.edit', $category->id) }}" class="bg-yellow-500 text-white py-1 px-2 rounded">Edit</a>
                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white py-1 px-2 rounded">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
