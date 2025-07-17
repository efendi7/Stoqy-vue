<template>
  <div v-if="message" :class="containerClasses">
    <span v-html="message"></span>
    <button
      @click="close"
      class="absolute top-2 right-2 text-xl leading-none font-bold hover:text-opacity-75"
    >
      &times;
    </button>
  </div>
</template>

<script setup>
import { computed } from 'vue'

// Props dari parent
const props = defineProps({
  type: {
    type: String,
    default: 'success' // success | error | warning | info
  },
  message: {
    type: String,
    required: true // Sebaiknya 'required' agar jelas komponen ini butuh message
  }
})

// Emit ke parent
const emit = defineEmits(['close'])

// Styling dinamis
const containerClasses = computed(() => {
  let base = 'mb-4 p-4 rounded-lg relative'
  switch (props.type) {
    case 'success':
      return `${base} bg-green-100 text-green-700 dark:bg-green-900 dark:bg-opacity-50 dark:text-green-300`
    case 'error':
      return `${base} bg-red-100 text-red-700 dark:bg-red-900 dark:bg-opacity-50 dark:text-red-300`
    case 'warning':
      return `${base} bg-yellow-100 text-yellow-700 dark:bg-yellow-900 dark:bg-opacity-50 dark:text-yellow-300`
    case 'info':
      return `${base} bg-blue-100 text-blue-700 dark:bg-blue-900 dark:bg-opacity-50 dark:text-blue-300`
    default:
      return base
  }
})

// Fungsi untuk memberitahu parent agar menutup komponen ini
const close = () => {
  emit('close')
}
</script>