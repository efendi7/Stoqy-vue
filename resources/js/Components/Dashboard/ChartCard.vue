<template>
  <!-- 
    Container utama disesuaikan dengan gaya panel standar:
    - bg-white dark:bg-gray-800
    - shadow-sm
    - rounded-lg
  -->
  <div
    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 animate-fade-in-up"
    :style="{ animationDelay: delay }"
  >
    <div class="flex flex-wrap items-center justify-between gap-4 mb-6">
      <!-- Warna teks judul disesuaikan -->
      <h3 class="text-xl font-semibold text-gray-800 flex items-center dark:text-gray-200">
        <span class="text-2xl mr-3 text-gray-500">{{ icon }}</span>{{ title }}
      </h3>
      
      <!-- Slot untuk filter tetap sama -->
      <slot name="filters"></slot>
    </div>

    <!-- Tampilan jika ada data -->
    <div v-if="hasData" class="h-80">
      <slot></slot>
    </div>
    
    <!-- Tampilan jika tidak ada data, warna teks disesuaikan -->
    <div v-else class="h-80 flex items-center justify-center text-center">
      <div>
        <div class="text-5xl mb-2 text-gray-300 dark:text-gray-600">{{ noDataIcon }}</div>
        <p class="text-gray-500 dark:text-gray-400">{{ noDataMessage }}</p>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  title: {
    type: String,
    required: true,
  },
  icon: {
    type: String,
    required: true,
  },
  hasData: {
    type: Boolean,
    default: true,
  },
  delay: {
    type: String,
    default: '0ms',
  },
  noDataIcon: {
    type: String,
    default: '📊',
  },
  noDataMessage: {
    type: String,
    default: 'Tidak ada data untuk ditampilkan.',
  },
});
</script>