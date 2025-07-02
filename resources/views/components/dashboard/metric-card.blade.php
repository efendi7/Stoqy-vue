{{-- resources/views/components/dashboard/metric-card.blade.php --}}
@props([
    'label',
    'value',
    'icon',
    'gradient' => 'from-gray-100 to-gray-200',
    'dark_gradient' => 'dark:from-gray-700 dark:to-gray-800',
    'border' => 'border-gray-200',
    'dark_border' => 'dark:border-gray-600',
    'text' => 'text-gray-800',
    'dark_text' => 'dark:text-gray-200',
    'delay' => 0,
])

<div class="backdrop-blur-xl bg-gradient-to-r {{ $gradient }} {{ $dark_gradient }} border {{ $border }} {{ $dark_border }} p-6 rounded-2xl shadow-lg transform hover:scale-105 transition-all duration-300 animate-fade-in-up"
    style="animation-delay: {{ $delay }}ms;">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm {{ $text }} {{ $dark_text }} font-medium">
                {{ $label }}
            </p>
            <p class="text-2xl font-bold {{ $text }} {{ $dark_text }} mt-1">
                {{ number_format($value) }}
            </p>
        </div>
        <div class="text-3xl opacity-70">{{ $icon }}</div>
    </div>
</div>