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
    <div class="overflow-x-auto bg-white rounded-lg shadow-lg">
        <table class="min-w-full border border-gray-300 rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-center border border-gray-300 text-sm uppercase font-bold">No</th>
                    <th class="py-3 px-4 text-left border border-gray-300 text-sm uppercase font-bold">Nama</th>
                    <th class="py-3 px-4 text-left border border-gray-300 text-sm uppercase font-bold">Email</th>
                    <th class="py-3 px-4 text-left border border-gray-300 text-sm uppercase font-bold">Role yang Diajukan</th>
                    <th class="py-3 px-4 text-center border border-gray-300 text-sm uppercase font-bold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300 bg-white bg-opacity-50">
                @forelse($users as $index => $user)
                <tr class="hover:bg-gray-100 transition-all">
                    <!-- No -->
                    <td class="py-3 px-4 text-center border border-gray-300">{{ $index + 1 }}</td>

                    <!-- Nama -->
                    <td class="py-3 px-4 text-left border border-gray-300">{{ $user->name }}</td>

                    <!-- Email -->
                    <td class="py-3 px-4 text-left border border-gray-300">{{ $user->email }}</td>

                    <!-- Role yang Diajukan -->
                    <td class="py-3 px-4 text-left border border-gray-300">
                        {{ ucfirst(str_replace('_', ' ', $user->requested_role)) }}
                    </td>

                    <!-- Aksi -->
                    <td class="py-3 px-4 text-center border border-gray-300">
                        <div class="flex justify-center gap-2">
                            <!-- Approve Form -->
                            <form action="{{ route('admin.approve.role', $user->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="approved_role" value="{{ $user->requested_role }}">
                                <button type="submit" class="bg-green-500 text-white px-3 py-1 rounded-lg hover:bg-green-600 transition-all">
                                    Setujui
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form action="{{ route('admin.reject.role', $user->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600 transition-all">
                                    Tolak
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-3 px-4 text-center text-gray-600">Tidak ada permintaan role yang ditemukan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $users->appends(request()->input())->links() }}
    </div>
</div>
@endsection
