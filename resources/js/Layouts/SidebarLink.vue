<template>
  <li>
    <Link :href="href" :class="linkClasses">
      <i :class="iconClasses"></i>
      
      <span class="font-medium flex-grow">
        <slot />
      </span>

      <span v-if="badge" class="ml-auto bg-red-500 text-white text-xs font-bold px-2 py-0.5 rounded-full animate-pulse">
        {{ badge }}
      </span>
    </Link>
  </li>
</template>

// File: resources/js/Layouts/SidebarLink.vue

<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
  href: { type: String, required: true },
  active: { type: Boolean, default: false },
  icon: { type: String, required: true },
  badge: { type: [String, Number], default: null },
});

// Computed property untuk kelas link utama (disesuaikan dengan tema teal)
const linkClasses = computed(() => {
  const baseClasses = 'flex items-center p-3 rounded-lg transition duration-200 group';
  
  if (props.active) {
    // Kelas saat link aktif
    return `${baseClasses} font-semibold shadow-sm bg-teal-100 text-teal-800 dark:bg-white/10 dark:text-white`;
  }
  // Kelas saat link tidak aktif
  return `${baseClasses} text-teal-700 hover:bg-teal-100/60 hover:text-teal-800 dark:text-gray-300 dark:hover:bg-white/5 dark:hover:text-white`;
});

// Computed property untuk kelas ikon (disesuaikan dengan tema teal)
const iconClasses = computed(() => {
  const baseClasses = `fas ${props.icon} w-6 text-center mr-3 text-lg`;

  if (props.active) {
    // Kelas ikon saat link aktif
    return `${baseClasses} text-teal-800 dark:text-white`;
  }
  // Kelas ikon saat link tidak aktif
  return `${baseClasses} text-teal-600 group-hover:text-teal-700 dark:text-gray-400 dark:group-hover:text-white`;
});
</script>