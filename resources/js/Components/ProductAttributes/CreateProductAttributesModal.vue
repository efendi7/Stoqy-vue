<script setup>
import { ref, watch, onMounted } from 'vue';
import { useForm, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  show: Boolean,
  products: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close']);

const isSubmitting = ref(false);
const errors = ref({});

const form = useForm({
  product_id: '',
  attribute_name: '',
  attribute_value: '',
});

const resetForm = () => {
  form.reset();
  errors.value = {};
};

const handleClose = () => {
  resetForm();
  emit('close');
};

const submitForm = async () => {
  if (isSubmitting.value) return;

  isSubmitting.value = true;
  errors.value = {};

  await router.post(route('product_attributes.store'), form, {
    onSuccess: () => {
      emit('close');
      resetForm();
    },
    onError: (e) => {
      errors.value = e;
    },
    onFinish: () => {
      isSubmitting.value = false;
    },
  });
};
</script>

<template>
  <Modal :show="show" @close="handleClose">
    <div class="p-6 bg-white dark:bg-gray-800 max-w-2xl mx-auto">
      <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">
        Tambah Atribut Produk
      </h2>

      <!-- Error Box -->
      <div v-if="Object.keys(errors).length > 0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:border-red-700 dark:text-red-300">
        <ul class="list-disc pl-5">
          <template v-for="(fieldErrors, field) in errors" :key="field">
            <li v-for="error in (Array.isArray(fieldErrors) ? fieldErrors : [fieldErrors])" :key="error">{{ error }}</li>
          </template>
        </ul>
      </div>

      <form @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 gap-6">
          <!-- Produk -->
          <div>
            <InputLabel for="product_id" value="Produk" />
            <select
              id="product_id"
              class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
              v-model="form.product_id"
              required
            >
              <option value="">Pilih Produk</option>
              <option v-for="product in products" :key="product.id" :value="product.id">
                {{ product.name }}
              </option>
            </select>
            <InputError class="mt-2" :message="form.errors?.product_id || errors.product_id?.[0]" />
          </div>

          <!-- Nama Atribut -->
          <div>
            <InputLabel for="attribute_name" value="Nama Atribut" />
            <TextInput
              id="attribute_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.attribute_name"
              required
            />
            <InputError class="mt-2" :message="form.errors?.attribute_name || errors.attribute_name?.[0]" />
          </div>

          <!-- Nilai Atribut -->
          <div>
            <InputLabel for="attribute_value" value="Nilai Atribut" />
            <TextInput
              id="attribute_value"
              type="text"
              class="mt-1 block w-full"
              v-model="form.attribute_value"
              required
            />
            <InputError class="mt-2" :message="form.errors?.attribute_value || errors.attribute_value?.[0]" />
          </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
          <SecondaryButton @click="handleClose" :disabled="isSubmitting">
            Batal
          </SecondaryButton>
          <PrimaryButton type="submit" class="ml-4" :disabled="isSubmitting" :class="{ 'opacity-50': isSubmitting }">
            {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
          </PrimaryButton>
        </div>
      </form>
    </div>
  </Modal>
</template>
