{{-- resources/views/components/dashboard/chart-card.blade.php --}}
@props(['title', 'icon', 'hasData' => true, 'delay' => '0ms'])

<div class="backdrop-blur-xl bg-white/80 border border-gray-200/50 p-6 md:p-8 rounded-2xl shadow-lg animate-fade-in-up dark:bg-white/5 dark:border-white/10"
    style="animation-delay: {{ $delay }};">
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center dark:text-gray-100">
            <span class="text-2xl mr-3">{{ $icon }}</span>{{ $title }}
        </h3>
        {{-- Slot untuk filter khusus chart --}}
        @if (isset($filters))
            {{ $filters }}
        @endif
    </div>

    @if ($hasData)
        <div class="h-80">
            {{ $slot }} {{-- Canvas chart akan ditempatkan di sini --}}
        </div>
    @else
        <div class="h-80 flex items-center justify-center text-center">
            <div>
                <div class="text-5xl mb-2 opacity-50">{{ $noDataIcon ?? '🚫' }}</div>
                <p class="text-gray-600 dark:text-gray-400">{{ $noDataMessage ?? 'Tidak ada data untuk ditampilkan.' }}</p>
            </div>
        </div>
    @endif
</div>