<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import Modal from '@/Components/Modal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  transaction: {
    type: Object,
    default: () => ({}),
  },
})

const emit = defineEmits(['close'])

const isDeleting = ref(false)

const confirmDelete = () => {
  if (!props.transaction?.id) return

  isDeleting.value = true

  router.delete(route('stock_transactions.destroy', props.transaction.id), {
    preserveScroll: true,
    onSuccess: () => {
      emit('close')
    },
    onFinish: () => {
      isDeleting.value = false
    },
  })
}
</script>

<template>
  <Modal :show="show" @close="$emit('close')" :closeable="!isDeleting">
    <div class="p-6 text-center">
      <div class="flex items-center justify-center w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900/20">
        <svg
          class="w-6 h-6 text-red-600 dark:text-red-400"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
        >
          <path
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.082 16.5c-.77.833.192 2.5 1.732 2.5z"
          ></path>
        </svg>
      </div>

      <div class="mt-4">
        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Konfirmasi Penghapusan
        </h3>
        <div v-if="transaction" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
          <p>Apakah Anda yakin ingin menghapus transaksi stok dengan ID:</p>
          <p class="font-semibold text-gray-800 dark:text-gray-200 mt-1">
            #{{ transaction.id }}
          </p>
          <p class="mt-3 text-red-600 dark:text-red-400 font-medium">
            Tindakan ini tidak dapat dibatalkan!
          </p>
        </div>
      </div>

      <div class="mt-6 flex justify-center space-x-4">
        <SecondaryButton @click="$emit('close')" :disabled="isDeleting">
          Batal
        </SecondaryButton>
        <DangerButton @click="confirmDelete" :disabled="isDeleting">
          <span v-if="isDeleting">Menghapus...</span>
          <span v-else>Ya, Hapus</span>
        </DangerButton>
      </div>
    </div>
  </Modal>
</template>
