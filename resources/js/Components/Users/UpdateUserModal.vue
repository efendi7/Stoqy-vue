<script setup>
import { useForm } from '@inertiajs/vue3';
import { watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

// Props untuk menampilkan/menyembunyikan modal dan menerima data pengguna
const props = defineProps({
    show: { type: Boolean, default: false },
    user: { type: Object, default: () => null },
});

// Emits untuk menutup modal
const emit = defineEmits(['close']);

// Inisialisasi form
const form = useForm({
    name: '',
    email: '',
    role: '',
    password: '',
    password_confirmation: '',
});

// Opsi untuk dropdown role
const roleOptions = [
    { value: 'admin', label: 'Admin' },
    { value: 'warehouse_manager', label: 'Manajer Gudang' },
    { value: 'warehouse_staff', label: 'Staff Gudang' },
];

// Watcher untuk mengisi form ketika prop 'user' berubah
watch(() => props.user, (newUser) => {
    if (newUser) {
        form.name = newUser.name;
        form.email = newUser.email;
        form.role = newUser.role;
    }
});

// Fungsi untuk mengirim data update
const submitUpdate = () => {
    if (!props.user) return;
    form.patch(route('users.update', props.user.id), {
        preserveScroll: true,
        onSuccess: () => {
            emit('close');
            form.reset();
        },
        onError: () => {
            form.reset('password', 'password_confirmation');
        }
    });
};

// Fungsi untuk menutup modal
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
                Edit Pengguna
            </h2>

            <form @submit.prevent="submitUpdate" class="space-y-6">
                <!-- Nama -->
                <div>
                    <InputLabel for="update-name" value="Nama" />
                    <TextInput id="update-name" type="text" class="mt-1 block w-full" v-model="form.name" required autocomplete="name" />
                    <InputError class="mt-2" :message="form.errors.name" />
                </div>

                <!-- Email -->
                <div>
                    <InputLabel for="update-email" value="Email" />
                    <TextInput id="update-email" type="email" class="mt-1 block w-full" v-model="form.email" required autocomplete="email" />
                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <!-- Role -->
                <div>
                    <InputLabel for="update-role" value="Role" />
                    <select id="update-role" v-model="form.role" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option v-for="option in roleOptions" :key="option.value" :value="option.value">
                            {{ option.label }}
                        </option>
                    </select>
                    <InputError class="mt-2" :message="form.errors.role" />
                </div>

                <!-- Password (Opsional) -->
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                     <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Isi kata sandi hanya jika Anda ingin mengubahnya.</p>
                    <div>
                        <InputLabel for="update-password" value="Password Baru" />
                        <TextInput id="update-password" type="password" class="mt-1 block w-full" v-model="form.password" autocomplete="new-password" />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>
                     <div class="mt-6">
                        <InputLabel for="update-password_confirmation" value="Konfirmasi Password Baru" />
                        <TextInput id="update-password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" autocomplete="new-password" />
                    </div>
                </div>

                <!-- Tombol Aksi -->
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <SecondaryButton @click="handleClose" :disabled="form.processing"> Batal </SecondaryButton>
                    <PrimaryButton type="submit" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>