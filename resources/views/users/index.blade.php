@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Daftar Pengguna</h1>

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

    {{-- Flash Message Error Validation --}}
    @if ($errors->any())
    <div id="flash-errors" class="max-w-lg mx-auto bg-red-600 text-white p-3 rounded-lg mb-6 shadow-lg backdrop-blur-md">
        <div class="font-bold">Ada kesalahan!</div>
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- Tombol Tambah Pengguna --}}
    <div class="flex flex-wrap gap-3 mb-6">
        <a href="{{ route('users.create') }}" class="bg-blue-500 text-white py-2 px-4 rounded-lg hover:bg-blue-600 transition-all">Tambah Pengguna</a>
    </div>

    {{-- Tabel Pengguna --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden border border-gray-300">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left border border-gray-300">Nama</th>
                    <th class="py-3 px-4 text-left border border-gray-300">Email</th>
                    <th class="py-3 px-4 text-left border border-gray-300">Role</th>
                    <th class="py-3 px-4 text-center border border-gray-300">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                        <td class="py-3 px-4 border border-gray-300">{{ $user->name }}</td>
                        <td class="py-3 px-4 border border-gray-300">{{ $user->email }}</td>
                        <td class="py-3 px-4 border border-gray-300 text-center">{{ app(\App\Services\UserService::class)->getRoleLabel($user->role) }}</td>
                        <td class="py-3 px-4 text-center border border-gray-300">
                            <div class="inline-flex gap-2">
                                <a href="{{ route('users.activity', $user->id) }}" class="bg-green-500 text-white py-1 px-4 rounded-lg hover:bg-green-600 transition-all">Aktivitas</a>
                                <a href="{{ route('users.edit', $user->id) }}" class="bg-yellow-500 text-white py-1 px-4 rounded-lg hover:bg-yellow-600 transition-all">Edit</a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $users->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>

<script>
    // Fungsi untuk menghilangkan Flash Message setelah 3 detik
    setTimeout(() => {
        const flashSuccess = document.getElementById('flash-success');
        const flashError = document.getElementById('flash-error');
        const flashErrors = document.getElementById('flash-errors');

        if (flashSuccess) {
            flashSuccess.style.opacity = '0';
            setTimeout(() => flashSuccess.remove(), 500); // Hapus elemen setelah efek
        }
        if (flashError) {
            flashError.style.opacity = '0';
            setTimeout(() => flashError.remove(), 500);
        }
        if (flashErrors) {
            flashErrors.style.opacity = '0';
            setTimeout(() => flashErrors.remove(), 500);
        }
    }, 3000); // Hilang dalam 3 detik
</script>
@endsection
