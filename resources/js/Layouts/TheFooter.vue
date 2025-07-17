<template>
  <footer class="w-full py-4 px-4 sm:px-6 lg:px-8 bg-teal-50 border-t border-teal-200 
                 dark:bg-[#161B22]/90 dark:backdrop-blur-lg dark:border-white/10
                 shadow-lg text-sm flex flex-wrap justify-center md:justify-between items-center gap-1">
    <div class="text-center md:text-start text-teal-700 dark:text-gray-400">
      &copy; {{ currentYear }} {{ appName }}. Hak Cipta Dilindungi.
    </div>
    <div class="text-center md:text-end text-teal-600 dark:text-gray-500">
      Dibuat di Yogyakarta | Versi {{ appVersion }}
    </div>
  </footer>
</template>

<script setup>
const props = defineProps({
  appName: {
    type: String,
    default: 'Nama Aplikasi'
  },
  appVersion: {
    type: String,
    default: '1.0.0'
  }
});

const currentYear = new Date().getFullYear();
const appName = props.appName;
const appVersion = props.appVersion;
</script>
