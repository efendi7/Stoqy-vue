<script setup>
import { computed, onMounted, onUnmounted, ref, watch } from 'vue';
import Scrollable from '@/Components/Scrollbar.vue';


const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  maxWidth: {
    type: String,
    default: '2xl',
  },
  closeable: {
    type: Boolean,
    default: true,
  },
});

const emit = defineEmits(['close']);
const dialog = ref();
const showSlot = ref(props.show);

watch(
  () => props.show,
  () => {
    if (props.show) {
      document.body.style.overflow = 'hidden';
      showSlot.value = true;
      dialog.value?.showModal();
    } else {
      document.body.style.overflow = '';
      setTimeout(() => {
        dialog.value?.close();
        showSlot.value = false;
      }, 200);
    }
  },
);

const close = () => {
  if (props.closeable) {
    emit('close');
  }
};

const closeOnEscape = (e) => {
  if (e.key === 'Escape') {
    e.preventDefault();
    if (props.show) {
      close();
    }
  }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));
onUnmounted(() => {
  document.removeEventListener('keydown', closeOnEscape);
  document.body.style.overflow = '';
});

const maxWidthClass = computed(() => {
  return {
    sm: 'sm:max-w-sm',
    md: 'sm:max-w-md',
    lg: 'sm:max-w-lg',
    xl: 'sm:max-w-xl',
    '2xl': 'sm:max-w-2xl',
  }[props.maxWidth];
});
</script>

<template>
  <dialog
    class="z-50 m-0 p-0 w-full h-full bg-transparent backdrop:bg-transparent overflow-hidden"
    ref="dialog"
  >
    <!-- Overlay -->
    <div
      class="fixed inset-0 z-50 flex items-center justify-center px-4 py-6 sm:px-0 overflow-y-auto"
      scroll-region
    >
      <!-- Backdrop -->
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0"
        enter-to-class="opacity-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100"
        leave-to-class="opacity-0"
      >
        <div
          v-show="showSlot"
          class="fixed inset-0 transition-opacity"
          @click="close"
        >
          <div class="absolute inset-0 bg-gray-500 dark:bg-gray-900 opacity-75" />
        </div>
      </Transition>

      <!-- Modal content -->
      <Transition
        enter-active-class="ease-out duration-300"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        enter-to-class="opacity-100 translate-y-0 sm:scale-100"
        leave-active-class="ease-in duration-200"
        leave-from-class="opacity-100 translate-y-0 sm:scale-100"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
      >
        <div
          v-show="showSlot"
          class="relative w-full transform overflow-auto max-h-screen rounded-lg bg-white dark:bg-gray-800 shadow-xl transition-all"
          :class="maxWidthClass"
        >
          <slot v-if="showSlot" />
        </div>
      </Transition>
    </div>
  </dialog>
</template>
