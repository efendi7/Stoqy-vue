<template>
  <div class="flex-grow overflow-y-auto pr-2 -mr-2 custom-scrollbar">
    <slot />
  </div>
</template>

<script setup>
// Komponen ini hanya slot container scrollable
</script>

<style scoped>
/* Optional: custom scrollbar agar ikut darkmode/lightmode */
.custom-scrollbar::-webkit-scrollbar {
  width: 8px;
  height: 8px; /* <-- TAMBAHKAN BARIS INI */
}

.custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.2);
  border-radius: 4px;
}

.dark .custom-scrollbar::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.2);
}
</style>a
