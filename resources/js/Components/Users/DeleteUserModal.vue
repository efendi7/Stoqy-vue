<script setup>
import { useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// Props
const props = defineProps({
    show: { type: Boolean, default: false },
    user: { type: Object, default: () => null },
});

// Emits
const emit = defineEmits(['close']);

// Form dummy hanya untuk state 'processing'
const form = useForm({});

// Fungsi hapus
const deleteUser = () => {
    if (!props.user) return;
    form.delete(route('users.destroy', props.user.id), {
        preserveScroll: true,
        onSuccess: () => emit('close'),
    });
};
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="lg">
        <div class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Hapus Pengguna
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                Apakah Anda yakin ingin menghapus pengguna
                <span class="font-bold text-gray-800 dark:text-gray-200">{{ user?.name }}</span>?
                Tindakan ini tidak dapat dibatalkan.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')" :disabled="form.processing">
                    Batal
                </SecondaryButton>
                <DangerButton @click="deleteUser" class="ml-3" :disabled="form.processing">
                    {{ form.processing ? 'Menghapus...' : 'Ya, Hapus' }}
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>