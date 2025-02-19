@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Supplier Baru</h1>
    <form action="{{ route('suppliers.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama Supplier</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label for="contact" class="block text-sm font-medium text-gray-700">Kontak</label>
            <input type="text" name="contact" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="contact" value="{{ old('contact') }}">
        </div>
        <div class="mb-4">
            <label for="address" class="block text-sm font-medium text-gray-700">Alamat</label>
            <input type="text" name="address" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="address" value="{{ old('address') }}">
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection
