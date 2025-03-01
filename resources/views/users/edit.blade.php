@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-2xl font-bold my-4">Edit Pengguna</h1>
    <form action="{{ route('users.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT') {{-- Menggunakan method PUT untuk update --}}

        {{-- Nama --}}
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" name="name" id="name" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 {{ $errors->has('name') ? 'border-red-500' : '' }}" 
                value="{{ old('name', $user->name) }}" required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Email --}}
        <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
            <input type="email" name="email" id="email" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 {{ $errors->has('email') ? 'border-red-500' : '' }}" 
                value="{{ old('email', $user->email) }}" required>
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password (Opsional) --}}
        <div class="mb-4">
            <label for="password" class="block text-sm font-medium text-gray-700">Password (Biarkan kosong jika tidak ingin mengubah)</label>
            <input type="password" name="password" id="password" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 {{ $errors->has('password') ? 'border-red-500' : '' }}">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Konfirmasi Password --}}
        <div class="mb-4">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
            <input type="password" name="password_confirmation" id="password_confirmation" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2">
        </div>

        {{-- Role --}}
        <div class="mb-4">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select name="role" id="role" 
                class="mt-1 block w-full border border-gray-300 rounded-md p-2 {{ $errors->has('role') ? 'border-red-500' : '' }}" required>
                <option value="Staff Gudang" {{ old('role', $user->role) == 'Staff Gudang' ? 'selected' : '' }}>Staff Gudang</option>
                <option value="Manajer Gudang" {{ old('role', $user->role) == 'Manajer Gudang' ? 'selected' : '' }}>Manajer Gudang</option>
                <option value="Admin" {{ old('role', $user->role) == 'Admin' ? 'selected' : '' }}>Admin</option>
            </select>
            @error('role')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        {{-- Tombol Simpan --}}
        <div class="mb-4">
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
