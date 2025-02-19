@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Kategori Baru</h1>
    
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
    
    <form action="{{ route('categories.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="description">{{ old('description') }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
