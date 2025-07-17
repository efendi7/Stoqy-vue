<script setup>
import { onMounted, ref, watch } from 'vue';
import { SunIcon, MoonIcon } from '@heroicons/vue/24/solid'; // Pastikan Anda sudah install @heroicons/vue

// Reactive state untuk melacak status dark mode
const isDarkMode = ref(false);

// Fungsi untuk menerapkan tema berdasarkan state
const applyTheme = () => {
  if (isDarkMode.value) {
    document.documentElement.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
};

// Fungsi untuk toggle dark mode
const toggleDarkMode = () => {
  isDarkMode.value = !isDarkMode.value;
};

// Gunakan watch untuk memanggil applyTheme setiap kali isDarkMode berubah
watch(isDarkMode, applyTheme);

// Cek preferensi saat komponen pertama kali dimuat (onMounted)
onMounted(() => {
  const savedTheme = localStorage.getItem('theme');
  if (savedTheme === 'dark') {
    isDarkMode.value = true;
  } else if (savedTheme === 'light') {
    isDarkMode.value = false;
  } else {
    // Jika tidak ada di localStorage, gunakan preferensi sistem operasi
    isDarkMode.value = window.matchMedia('(prefers-color-scheme: dark)').matches;
  }
});
</script>

<template>
  <button
    @click="toggleDarkMode"
    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
  >
    <MoonIcon v-if="!isDarkMode" class="h-6 w-6" />
    <SunIcon v-else class="h-6 w-6" />
  </button>
</template>