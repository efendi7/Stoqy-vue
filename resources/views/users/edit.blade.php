@extends('layouts.app')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-cover bg-center">
    <div class="bg-white bg-opacity-70 p-6 rounded-2xl shadow-xl w-full max-w-md animate-fadeIn">
        <h1 class="text-xl font-bold text-gray-800 text-center mb-6">Edit Pengguna</h1>
        
        {{-- Flash Message Error --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('users.update', $user->id) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT') {{-- Menggunakan method PUT untuk update --}}

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-medium text-gray-800">Nama</label>
                <input type="text" name="name" id="name" 
                    class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('name') ? 'border-red-500' : '' }}" 
                    value="{{ old('name', $user->name) }}" required>
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-medium text-gray-800">Email</label>
                <input type="email" name="email" id="email" 
                    class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('email') ? 'border-red-500' : '' }}" 
                    value="{{ old('email', $user->email) }}" required>
            </div>

            {{-- Password (Opsional) --}}
            <div>
                <label for="password" class="block text-sm font-medium text-gray-800">Password <span class="text-gray-500">(Opsional)</span></label>
                <input type="password" name="password" id="password" 
                    class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('password') ? 'border-red-500' : '' }}">
            </div>

            {{-- Konfirmasi Password --}}
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-800">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" 
                    class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all">
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-medium text-gray-800">Role</label>
                <select name="role" id="role" 
                    class="w-full mt-1 p-2 rounded-lg border border-gray-300 text-gray-800 focus:ring-2 focus:ring-blue-500 transition-all {{ $errors->has('role') ? 'border-red-500' : '' }}" required>
                    <option value="warehouse_staff" {{ old('role', $user->role) == 'warehouse_staff' ? 'selected' : '' }}>Staff Gudang</option>
                    <option value="warehouse_manager" {{ old('role', $user->role) == 'warehouse_manager' ? 'selected' : '' }}>Manajer Gudang</option>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            {{-- Tombol Simpan --}}
            <button type="submit" 
                class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-all transform hover:scale-105">Simpan Perubahan</button>
        </form>
    </div>
</div>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .animate-fadeIn {
        animation: fadeIn 0.8s ease-out;
    }
</style>
@endsection
