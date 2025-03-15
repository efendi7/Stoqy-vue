@extends('layouts.app')

@section('title', 'Permintaan Verifikasi Role')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-gray-100 mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-gray-700 text-center">Permintaan Verifikasi Role</h1>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="bg-green-500 border-l-4 border-green-700 text-white px-4 py-3 rounded-lg mb-6 shadow-md" role="alert">
            <strong class="font-bold">Berhasil!</strong>
            <p class="mt-2 text-sm">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Tabel Permintaan Verifikasi Role --}}
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
        <table class="min-w-full bg-white rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left text-sm uppercase font-bold">Nama</th>
                    <th class="py-3 px-4 text-left text-sm uppercase font-bold">Email</th>
                    <th class="py-3 px-4 text-left text-sm uppercase font-bold">Role yang Diajukan</th>
                    <th class="py-3 px-4 text-center text-sm uppercase font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($users as $user)
                    <tr class="hover:bg-gray-100 transition-all">
                        <td class="py-3 px-4 text-gray-800">{{ $user->name }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ $user->email }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ ucfirst(str_replace('_', ' ', $user->requested_role)) }}</td>
                        <td class="py-3 px-4 text-center">
                            <div class="inline-flex gap-2">
                                <form action="{{ route('approve.role', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="approved_role" value="{{ $user->requested_role }}">
                                    <button type="submit" class="bg-green-500 text-white py-1 px-4 rounded-lg hover:bg-green-600 transition-all">
                                        Setujui
                                    </button>
                                </form>
                                <form action="{{ route('reject.role', $user->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-red-500 text-white py-1 px-4 rounded-lg hover:bg-red-600 transition-all">
                                        Tolak
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-3 px-4 text-center text-gray-600">Tidak ada permintaan role yang ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>


</div>
@endsection
