@extends('layouts.app') {{-- Ganti dengan layout utama aplikasi Anda --}}

@section('content')
    {{-- Bagian ini akan menampilkan data umum yang selalu ada --}}
    <header class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">Selamat Datang, {{ $userName ?? 'Pengguna' }}!</h1>
        <p class="text-gray-500 dark:text-gray-400">Anda login sebagai {{ $userRoleLabel ?? 'User' }}.</p>
    </header>

    {{-- 
        Di sinilah keajaibannya:
        Blade akan memuat partial view yang sesuai dengan role user.
        Nama view ($dashboardView) dikirim dari DashboardService melalui controller.
        Contoh: 'dashboard.admin', 'dashboard.warehouse_manager', dll.
    --}}
    @include($dashboardView, ['data' => $data])

@endsection