<template> 
  <Modal :show="show" @close="$emit('close')" :closeable="!isDeleting">
    <div class="p-6">
      <!-- Icon Warning -->
      <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900/20">
        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
        </svg>
      </div>

      <!-- Title & Description -->
      <div class="mt-4 text-center">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Konfirmasi Penghapusan
        </h3>
        <div class="mt-2 text-sm text-gray-500 dark:text-gray-400">
          <p>Apakah Anda yakin ingin menghapus produk ini?</p>

          <!-- Product Info Centered -->
          <div
            v-if="product"
            class="mt-4 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg border dark:border-gray-600 max-w-md mx-auto text-center"
          >
            <!-- Gambar di atas -->
            <div class="flex justify-center">
              <img
                v-if="product.image_url"
                :src="product.image_url"
                class="w-24 h-24 rounded-lg object-cover border dark:border-gray-600"
                alt="Product Image"
              />
            </div>

            <!-- Nama Produk dan Detail -->
            <div class="mt-4">
              <p class="font-semibold text-gray-900 dark:text-gray-100 text-base">
                {{ product.name }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">
                <span class="font-medium">SKU:</span> {{ product.sku }}
              </p>
              <p class="text-sm text-gray-600 dark:text-gray-300">
                <span class="font-medium">Stok:</span> {{ product.stock }}
              </p>
            </div>
          </div>

          <!-- Warning -->
          <p class="mt-3 text-red-600 dark:text-red-400 font-medium">
            Tindakan ini tidak dapat dibatalkan!
          </p>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="mt-6 flex justify-center space-x-4">
        <SecondaryButton @click="$emit('close')" :disabled="isDeleting" class="px-6 py-2">
          Batal
        </SecondaryButton>
        <DangerButton @click="confirmDelete" :disabled="isDeleting" class="px-6 py-2">
          <span v-if="isDeleting" class="flex items-center">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
              viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor"
                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
            Menghapus...
          </span>
          <span v-else>Ya, Hapus</span>
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  product: {
    type: Object,
    default: () => ({})
  }
});

const emit = defineEmits(['close', 'deleted']);
const isDeleting = ref(false);

const confirmDelete = () => {
  if (!props.product.id) return;

  isDeleting.value = true;

  router.delete(route('products.destroy', props.product.id), {
    preserveScroll: true,
    onSuccess: () => {
      emit('deleted');
      emit('close');
    },
    onError: () => {},
    onFinish: () => {
      isDeleting.value = false;
    }
  });
};
</script>
