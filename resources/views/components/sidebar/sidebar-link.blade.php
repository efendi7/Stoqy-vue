@props([
    'href',
    'active' => false,
    'icon',
    'badge' => null
])

@php
    // Logika untuk menentukan kelas CSS berdasarkan status aktif atau tidak
    $classes = ($active ?? false)
                ? 'flex items-center p-3 rounded-lg bg-white/10 text-white font-semibold shadow-sm' // Kelas saat tautan aktif
                : 'flex items-center p-3 rounded-lg text-gray-300 hover:bg-white/5 hover:text-white transition duration-200 group'; // Kelas default
@endphp

<li>
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{-- Ikon --}}
        <i class="fas {{ $icon }} w-6 text-center mr-3 text-lg {{ $active ? 'text-white' : 'text-gray-400 group-hover:text-white' }}"></i>
        
        {{-- Teks Tautan (Slot) --}}
        <span class="font-medium">{{ $slot }}</span>

        {{-- Lencana Notifikasi (jika ada) --}}
        @if($badge)
            <span class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full animate-pulse">
                {{ $badge }}
            </span>
        @endif
    </a>
</li>