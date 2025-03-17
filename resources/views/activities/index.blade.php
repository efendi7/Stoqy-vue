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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        setTimeout(function () {
            let flashMessage = document.getElementById("flash-success");
            if (flashMessage) {
                flashMessage.classList.add("opacity-0", "transition-opacity", "duration-500");
                setTimeout(() => flashMessage.remove(), 500);
            }
        }, 3000);
    });
    </script>
    @endif

    {{-- Bagian Filter Periode --}}
    <div class="bg-white p-6 rounded-lg shadow-md mb-8">
        <h3 class="text-gray-600 text-sm font-medium mb-4">Filter Periode</h3>
        <form id="filterForm" action="{{ route('laporan.aktivitas') }}" method="GET" class="flex flex-wrap gap-4">
            <div class="flex items-center">
                <label for="tanggal_mulai" class="mr-2 text-sm">Dari</label>
                <input type="date" id="tanggal_mulai" name="tanggal_mulai" value="{{ $startDate }}" 
                       class="border rounded px-2 py-1 text-sm">
            </div>

            <div class="flex items-center">
                <label for="tanggal_akhir" class="mr-2 text-sm">Sampai</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ $endDate }}" 
                       class="border rounded px-2 py-1 text-sm">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600">Terapkan</button>
                <button type="button" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600" onclick="resetToDefault()">Reset</button>
            </div>
        </form>
    </div>

    {{-- Tombol Hapus Semua Log --}}
    <div class="mb-4 flex justify-end">
        <form action="{{ route('activities.delete-all') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus semua log aktivitas?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow-md">
                Hapus Semua Log
            </button>
        </form>
    </div>

    {{-- Tabel Aktivitas --}}
    <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
        <table class="min-w-full border-collapse border border-gray-300">
            <thead class="bg-gray-800 bg-opacity-70 text-white">
                <tr>
                    <th class="py-3 px-4 border border-gray-300 text-left">Nama Pengguna</th>
                    <th class="py-3 px-4 border border-gray-300 text-left">Role</th>
                    <th class="py-3 px-4 border border-gray-300 text-left">Aksi</th>
                    <th class="py-3 px-4 border border-gray-300 text-center">Waktu</th>
                    <th class="py-3 px-4 border border-gray-300 text-center">Hapus</th> 
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @forelse($aktivitas as $log)
                    <tr class="hover:bg-gray-100 transition">
                        <td class="py-3 px-4 border border-gray-300">{{ optional($log->user)->name ?? 'Tidak Diketahui' }}</td>
                        <td class="py-3 px-4 border border-gray-300">{{ optional($log->user)->role ?? 'Tanpa Role' }}</td>
                        <td class="py-3 px-4 border border-gray-300">{{ $log->action }}</td>
                        <td class="py-3 px-4 border border-gray-300 text-center">{{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                        <td class="py-3 px-4 border border-gray-300 text-center">
                            <form action="{{ route('laporan.aktivitas.hapus', $log->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus log ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-3 px-4 border border-gray-300 text-center text-gray-500">
                            Tidak ada data aktivitas untuk ditampilkan
                        </td>
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

<script>
    function resetToDefault() {
        const form = document.getElementById('filterForm');
        const today = new Date().toISOString().split('T')[0]; 
        const oneMonthAgo = new Date();
        oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
        const lastMonth = oneMonthAgo.toISOString().split('T')[0];

        form.tanggal_mulai.value = lastMonth;
        form.tanggal_akhir.value = today;
        form.submit();
    }
</script>

@endsection
