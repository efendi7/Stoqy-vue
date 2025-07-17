<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// Props untuk menampilkan/menyembunyikan modal
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
});

// Emits untuk menutup modal
const emit = defineEmits(['close']);

// Inisialisasi form dengan field yang sesuai untuk pengguna
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    role: 'warehouse_staff', // Nilai default
});

// Opsi untuk dropdown role
const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'warehouse_manager', label: 'Manajer Gudang' },
    { value: 'warehouse_staff', label: 'Staff Gudang' },
];

// Fungsi untuk mengirim data form
const submitUser = () => {
    form.post(route('users.store'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close'); // Tutup modal jika berhasil
            form.reset(); // Kosongkan form
        },
        onError: () => {
            // Error akan ditangani oleh form.errors secara otomatis
            // Fokuskan kembali ke input password jika ada error
            form.reset('password', 'password_confirmation');
        },
    });
};

// Fungsi untuk menutup modal dan mereset form
const handleClose = () => {
    form.reset();
    form.clearErrors();
    emit('close');
};
</script>

<template>
    <Modal :show="show" @close="handleClose">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                Tambah Pengguna Baru
            </h2>

            <form @submit.prevent="submitUser" class="space-y-6">
                <!-- Nama -->
                <div>
                    <InputLabel for="name" value="Nama" />
                    <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Password -->
                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <InputLabel for="password_confirmation" value="Konfirmasi Password" />
                    <TextInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
                    <InputError class="mt-2" :message="form.errors.password_confirmation" />
                </div>

                <!-- Role -->
                <div>
                    <InputLabel for="role" value="Role" />
                    <select id="role" v-model="form.role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option v-for="option in roleOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <SecondaryButton @click="handleClose" :disabled="form.processing"> Batal </SecondaryButton>
                    <PrimaryButton type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>