@extends('layouts.app')

@section('content')
<div class="container max-w-3xl mx-auto mt-16">
    <h1 class="text-2xl font-extrabold mb-6 text-slate-600 text-center">Edit Profil</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-700 p-4 rounded-md shadow mb-6">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-lg">
        @csrf

        <!-- Foto Profil -->
        <div class="mb-6 flex flex-col items-center">
            @if($user->profile_picture)
                <div class="relative">
                    <img src="{{ asset('storage/' . $user->profile_picture) }}" 
                         alt="Foto Profil" 
                         class="w-32 h-32 rounded-full border-4 border-blue-500 shadow-md object-cover">
                </div>
            @else
                <div class="w-32 h-32 flex items-center justify-center bg-gray-200 text-gray-500 rounded-full border-4 border-gray-300 shadow-md">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                </div>
            @endif

            <label for="profile_picture" class="mt-4 cursor-pointer text-sm text-blue-600 hover:underline">
                Ganti Foto Profil
            </label>
            <input type="file" id="profile_picture" name="profile_picture" class="hidden">
            @error('profile_picture')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Nama -->
        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
            <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" 
                   class="mt-1 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2 sm:text-sm">
            @error('name')
                <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Simpan -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg shadow hover:bg-blue-600 focus:outline-none transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
