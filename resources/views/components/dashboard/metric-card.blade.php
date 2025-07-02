@props(['label', 'value', 'gradient', 'border', 'text', 'icon', 'delay', 'dark_gradient', 'dark_border', 'dark_text'])

<div class="backdrop-blur-xl bg-gradient-to-br {{ $gradient }} border {{ $border }} p-6 rounded-2xl shadow-lg transform hover:scale-105 hover:-translate-y-2 transition-all duration-500 group animate-fade-in-up
            {{ $dark_gradient ?? '' }} {{ $dark_border ?? '' }} dark:shadow-2xl dark:hover:shadow-3xl"
    style="animation-delay: {{ $delay }}ms;">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-lg font-semibold {{ $text }} mb-2 opacity-90 {{ $dark_text ?? '' }}">
                {{ $label }}</p>
            <p
                class="text-3xl font-bold {{ $text }} group-hover:scale-110 transition-transform duration-300 {{ $dark_text ?? '' }}">
                {{ $value }}
            </p>
        </div>
        <div
            class="text-4xl opacity-80 group-hover:scale-110 group-hover:rotate-12 transition-all duration-300">
            {{ $icon }}
        </div>
    </div>
    <div
        class="absolute inset-0 bg-gradient-to-r {{ $gradient }} rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500 -z-10
                {{ $dark_gradient ?? '' }} ">
    </div>
</div>