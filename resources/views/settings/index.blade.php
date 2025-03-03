@extends('layouts.app')

@section('content')
@php
    $setting = App\Models\Setting::first();
@endphp

<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Pengaturan Umum</h1>

    {{-- Flash Message Sukses --}}
    @if(session('success'))
    <div id="flash-success" class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
    @endif

    {{-- Flash Message Error --}}
    @if(session('error'))
    <div id="flash-error" class="max-w-lg mx-auto bg-red-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg transition-opacity opacity-90 hover:opacity-100 backdrop-blur-md mt-4">
        <div class="flex items-center space-x-2">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-white font-bold hover:text-gray-200">✖</button>
    </div>
    @endif

    {{-- Form Pengaturan --}}
    <div class="max-w-4xl mx-auto bg-white bg-opacity-50 shadow-md rounded-lg p-6">
        <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Nama Aplikasi -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Aplikasi</label>
                <input type="text" name="app_name" value="{{ $setting->app_name ?? config('app.name') }}" 
                       class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Logo Aplikasi -->
            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Logo Aplikasi</label>
                <input type="file" name="app_logo" class="w-full border rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                
                @if ($setting && $setting->logo)
                    <img src="{{ asset('storage/' . $setting->logo) }}" class="mt-2 w-24 h-24 rounded-lg shadow-md">
                @else
                    <img src="{{ asset('img/default-logo.png') }}" class="mt-2 w-24 h-24 rounded-lg shadow-md">
                @endif
            </div>

            <div class="flex justify-center">
                <button type="submit" class="bg-purple-700 text-white px-6 py-2 rounded-lg hover:bg-purple-800 transition-all">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Flash message otomatis hilang setelah 5 detik
    setTimeout(function() {
        const successMessage = document.getElementById('flash-success');
        const errorMessage = document.getElementById('flash-error');
        
        if (successMessage) {
            successMessage.remove();
        }
        if (errorMessage) {
            errorMessage.remove();
        }
    }, 5000); // 5000 ms = 5 detik
</script>
@endsection
