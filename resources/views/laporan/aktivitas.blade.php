@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-4">Laporan Aktivitas Pengguna</h1>

    <div class="overflow-x-auto">
        <table class="w-full border border-gray-300">
            <thead>
                <tr class="bg-gray-100 text-gray-700">
                    <th class="border p-2">Nama Pengguna</th>
                    <th class="border p-2">Aksi</th>
                    <th class="border p-2">Waktu</th>
                </tr>
            </thead>
            <tbody>
                @foreach($aktivitas as $log)
                <tr class="border text-gray-800">
                    <td class="border p-2">{{ $log->name }}</td>
                    <td class="border p-2">{{ $log->action }}</td>
                    <td class="border p-2 text-center">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y H:i') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
