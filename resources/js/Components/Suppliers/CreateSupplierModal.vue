<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close']);

const form = useForm({
  name: '',
  contact: '',
  address: '',
  email: '',
});

const submit = () => {
  form.post(route('suppliers.store'), {
    preserveScroll: true,
    onSuccess: () => {
      form.reset();
      emit('close');
    },
  });
};

const handleClose = () => {
  form.reset();
  emit('close');
};
</script>

<template>
  <Modal :show="show" @close="handleClose">
    <div class="p-6 bg-white dark:bg-gray-800 animate-fadeIn rounded-2xl">
      <h2 class="text-xl font-bold text-gray-800 dark:text-white text-center mb-6">
        Tambah Supplier Baru
      </h2>

      <form @submit.prevent="submit" class="space-y-4">
        <div>
          <InputLabel for="name" value="Nama Supplier" />
          <TextInput
            id="name"
            v-model="form.name"
            type="text"
            class="mt-1 block w-full"
            required
          />
          <InputError class="mt-2" :message="form.errors.name" />
        </div>

        <div>
          <InputLabel for="contact" value="Kontak" />
          <TextInput
            id="contact"
            v-model="form.contact"
            type="text"
            class="mt-1 block w-full"
          />
          <InputError class="mt-2" :message="form.errors.contact" />
        </div>

        <div>
          <InputLabel for="address" value="Alamat" />
          <TextInput
            id="address"
            v-model="form.address"
            type="text"
            class="mt-1 block w-full"
          />
          <InputError class="mt-2" :message="form.errors.address" />
        </div>

        <div>
          <InputLabel for="email" value="Email" />
          <TextInput
            id="email"
            v-model="form.email"
            type="email"
            class="mt-1 block w-full"
          />
          <InputError class="mt-2" :message="form.errors.email" />
        </div>

        <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
          <SecondaryButton @click="handleClose" :disabled="form.processing">
            Batal
          </SecondaryButton>
          <PrimaryButton
            type="submit"
            class="ml-4"
            :class="{ 'opacity-25': form.processing }"
            :disabled="form.processing"
          >
            {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
          </PrimaryButton>
        </div>
      </form>
    </div>
  </Modal>
</template>

<style scoped>
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(-20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
.animate-fadeIn {
  animation: fadeIn 0.8s ease-out;
}
</style>
