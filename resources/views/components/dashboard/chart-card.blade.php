@props(['id', 'title', 'icon', 'delay'])

<div class="backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg hover:shadow-xl transition-all duration-500 group animate-fade-in-up
            dark:bg-white/5 dark:border-white/10 dark:shadow-2xl dark:hover:shadow-3xl"
    style="animation-delay: {{ $delay }}ms;">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center dark:text-gray-100">
            <span
                class="text-2xl mr-3 group-hover:scale-110 transition-transform duration-300">{{ $icon }}</span>
            {{ $title }}
        </h3>
        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
    </div>
    <div class="relative">
        <canvas id="{{ $id }}"
            class="w-full h-64 transition-all duration-500 group-hover:scale-[1.02]"></canvas>
        <div
            class="absolute inset-0 bg-gradient-to-t from-blue-500/3 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500 rounded-lg
                     dark:from-blue-600/5">
        </div>
    </div>
</div>