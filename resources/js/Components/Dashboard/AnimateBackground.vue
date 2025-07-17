<template>
  <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10">
    <div
      class="absolute -top-40 -right-40 w-80 h-80 bg-blue-500/5 rounded-full blur-3xl animate-pulse dark:bg-blue-600/10"
    ></div>
    <div
      class="absolute -bottom-40 -left-40 w-80 h-80 bg-purple-500/5 rounded-full blur-3xl animate-pulse delay-1000 dark:bg-purple-600/10"
    ></div>
    <div
      class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-indigo-500/3 rounded-full blur-3xl animate-spin-slow dark:bg-indigo-600/5"
    ></div>
  </div>
</template>

<script setup>
// Tidak perlu script karena komponen ini murni visual (presentational).
</script>