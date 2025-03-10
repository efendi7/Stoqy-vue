@extends('layouts.app')

@section('title', 'Laporan Aktivitas Pengguna')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Laporan Aktivitas Pengguna</h1>

    {{-- Notifikasi Kesalahan --}}
    @if ($errors->any())
        <div class="bg-red-600 border border-red-400 text-white px-4 py-3 rounded-lg relative mb-6" role="alert">
            <strong class="font-bold">Ada kesalahan!</strong>
            <ul class="mt-2">
                @foreach ($errors->all() as $error)
                    <li>- {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Filter Form --}}
    <form action="{{ route('laporan.aktivitas') }}" method="GET" class="mb-6 flex flex-wrap gap-4 bg-white p-4 rounded-lg shadow">
        <div>
            <label for="tanggal_mulai" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
            <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ request('tanggal_mulai') }}" 
                   class="border p-2 rounded-lg w-full">
        </div>

        <div>
            <label for="tanggal_akhir" class="block text-sm font-medium text-gray-700 mb-1">Tanggal Akhir</label>
            <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" 
                   class="border p-2 rounded-lg w-full">
        </div>

        <div>
            <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition">Filter</button>
            <a href="{{ route('laporan.aktivitas') }}" class="bg-gray-500 text-white px-6 py-2 rounded-lg ml-2 hover:bg-gray-600 transition">Reset</a>
        </div>
    </form>

    {{-- Tabel Aktivitas --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nama Pengguna</th>
                    <th class="py-3 px-4 text-left">Aksi</th>
                    <th class="py-3 px-4 text-center">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse($aktivitas as $log)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="py-3 px-4">{{ optional($log->user)->name ?? 'Tidak Diketahui' }}</td>
                        <td class="py-3 px-4">{{ $log->action }}</td>
                        <td class="py-3 px-4 text-center">{{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="py-3 px-4 text-center text-gray-500">Tidak ada data aktivitas untuk ditampilkan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-6 flex justify-center">
        {{ $aktivitas->appends(request()->input())->links('vendor.pagination.custom') }}
    </div>
</div>
@endsection
