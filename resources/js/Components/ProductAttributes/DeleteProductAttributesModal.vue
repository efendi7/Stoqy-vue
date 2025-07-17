<script setup>
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  show: Boolean,
  attribute: Object,
});

const emit = defineEmits(['close']);

const submit = () => {
  router.delete(route('product_attributes.destroy', props.attribute.id), {
    onSuccess: () => emit('close'),
  });
};
</script>

<template>
  <Modal :show="show" @close="emit('close')">
    <div class="p-6">
      <h2 class="text-xl font-semibold text-red-600 mb-4">Konfirmasi Hapus</h2>
      <p class="mb-4">Yakin ingin menghapus atribut <strong>{{ attribute?.attribute_name }}</strong>?</p>

      <div class="flex justify-end gap-2">
        <button @click="emit('close')" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
          Batal
        </button>
        <button @click="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
          Hapus
        </button>
      </div>
    </div>
  </Modal>
</template>
