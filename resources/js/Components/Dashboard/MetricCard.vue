<template>
  <div
    class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 p-5 rounded-lg shadow-sm transform hover:scale-105 transition-transform duration-300 animate-fade-in-up"
    :style="{ animationDelay: `${delay}ms` }"
  >
    <div class="flex items-center justify-between">
      <div>
        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">
          {{ label }}
        </p>
        <p class="text-2xl font-bold mt-1 text-gray-900 dark:text-gray-100">
          {{ formattedValue }}
        </p>
      </div>
      <div class="text-3xl text-gray-400 dark:text-gray-500">{{ icon }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  label: {
    type: String,
    required: true,
  },
  value: {
    type: [Number, String], // Dibuat fleksibel
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  delay: {
    // FIX: Izinkan Number dan String agar tidak muncul warning
    type: [Number, String], 
    default: 0,
  },
});

const formattedValue = computed(() => {
  const num = Number(props.value);
  if (isNaN(num)) return props.value;
  return num.toLocaleString('id-ID'); 
});
</script>
