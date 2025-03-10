@extends('layouts.app')

@section('title', 'Laporan Aktivitas Pengguna')

@section('content')
<div class="container mx-auto px-4 mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">Laporan Aktivitas Pengguna</h1>

    <div class="overflow-x-auto rounded-lg shadow-lg bg-white">
        <table class="w-full table-auto border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border p-2">Nama Pengguna</th>
                    <th class="border p-2">Aksi</th>
                    <th class="border p-2">Waktu</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-300">
                @foreach($aktivitas as $log)
                <tr class="hover:bg-gray-100 transition">
                    <td class="border p-2 text-center">
                        {{ optional($log->user)->name ?? 'Tidak Diketahui' }}
                    </td>
                    <td class="border p-2">
                        {{ $log->action }}
                    </td>
                    <td class="border p-2 text-center">
                        {{ \Carbon\Carbon::parse($log->created_at)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $aktivitas->appends(request()->input())->links() }}
    </div>
</div>
@endsection
