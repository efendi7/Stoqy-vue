@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 min-h-screen bg-cover bg-center mt-16">
    <h1 class="text-3xl font-extrabold my-6 text-slate-600 text-center">
        Aktivitas {{ $user->name }}
    </h1>

    {{-- Jika tidak ada aktivitas --}}
    @if($activities->isEmpty())
        <p class="text-center text-gray-500">Belum ada aktivitas yang tercatat.</p>
    @else
        <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50">
            <table class="min-w-full bg-white bg-opacity-50 rounded-lg shadow overflow-hidden border border-gray-300">
                <thead class="bg-gray-800 bg-opacity-70 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left border border-gray-300">Aktivitas</th>
                        <th class="py-3 px-4 text-left border border-gray-300">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    @foreach ($activities as $activity)
                        <tr class="hover:bg-gray-100 bg-white bg-opacity-50 transition-all">
                            <td class="py-3 px-4 border border-gray-300">{{ ucfirst($activity->action) }}</td>
                            <td class="py-3 px-4 border border-gray-300">{{ $activity->created_at->format('d M Y H:i') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="mt-6 flex justify-center">
            {{ $activities->links() }}
        </div>
    @endif
</div>
@endsection
