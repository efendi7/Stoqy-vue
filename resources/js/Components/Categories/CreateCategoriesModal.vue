<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// Props untuk menampilkan/menyembunyikan modal
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

// Emits untuk menutup modal
const emit = defineEmits(['close']);

// Inisialisasi form dengan field yang sesuai untuk kategori
const form = useForm({
    name: '',
    description: '',
});

// Fungsi untuk mengirim data form
const submitCategory = () => {
    form.post(route('categories.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close'); // Tutup modal jika berhasil
            form.reset(); // Kosongkan form
        },
        onError: () => {
            // Error akan ditangani oleh form.errors secara otomatis
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
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                Tambah Kategori Baru
            </h2>

            <form @submit.prevent="submitCategory" class="space-y-6">
                <div>
                    <InputLabel for="name" value="Nama Kategori" />
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="off"
                    />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <div>
                    <InputLabel for="description" value="Deskripsi" />
                    <textarea
                        id="description"
                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                        v-model="form.description"
                        rows="4"
                    ></textarea>
                    <InputError class="mt-2" :message="form.errors.description" />
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