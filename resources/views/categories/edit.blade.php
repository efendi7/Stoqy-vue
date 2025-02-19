@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Edit Kategori</h1>
    <form action="{{ route('categories.update', $category->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ $category->name }}" required>
        </div>
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="description" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="description">{{ $category->description }}</textarea>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
