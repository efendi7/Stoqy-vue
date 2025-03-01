@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Tambah Pengguna</h1>
    <form action="{{ route('users.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="email" value="{{ old('email') }}" required>
        </div>
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
            <input type="password" name="password" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="password" required>
        </div>
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" class="mt-1 block w-full border border-gray-300 rounded-md p-2" id="password_confirmation" required>
        </div>
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" id="role" class="mt-1 block w-full border border-gray-300 rounded-md p-2" required>
                <option value="warehouse_staff">Staff Gudang</option>
                <option value="warehouse_manager">Manajer Gudang</option>
                <option value="admin">Admin</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
    </form>
</div>
@endsection