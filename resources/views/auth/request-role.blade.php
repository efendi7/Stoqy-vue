@extends('layouts.app')

@section('title', 'Ajukan Role')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-gray-100 mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-gray-700 text-center">Ajukan Role</h1>

    <p class="text-center text-gray-600 mb-6">
        Akun Anda belum memiliki akses. Silakan ajukan role agar admin dapat memverifikasinya.
    </p>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-500 border-l-4 border-green-700 text-white px-4 py-3 rounded-lg mb-6 shadow-md" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <p class="mt-2 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Notifikasi Error --}}
    @if(session('error'))
        <div class="bg-red-500 border-l-4 border-red-700 text-white px-4 py-3 rounded-lg mb-6 shadow-md" role="alert">
            <strong class="font-bold">Gagal!</strong>
            <p class="mt-2 text-sm">{{ session('error') }}</p>
        </div>
    @endif

    {{-- Form Ajukan Role --}}
    <div class="bg-white rounded-lg shadow-lg p-6 max-w-lg mx-auto">
        <form action="{{ route('request.role') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Pilih Role:</label>
                <select name="requested_role" required class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="warehouse_manager">Manajer Gudang</option>
                    <option value="warehouse_staff">Staff Gudang</option>
                </select>
            </div>
            <div class="mt-6">
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-all w-full">
                    Ajukan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
