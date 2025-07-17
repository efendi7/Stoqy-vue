<script setup>
import { ref, watch, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// Props
const props = defineProps({
    show: Boolean,
    supplier: Object,
});

const emit = defineEmits(['close', 'updated']);

// State
const isEditMode = ref(false);
const isSubmitting = ref(false);
const errors = ref({});
const form = ref({
    name: '',
    contact: '',
    address: '',
    email: '',
});

// Watch supplier changes
watch(() => props.supplier, (newVal) => {
    if (newVal) {
        form.value = {
            name: newVal.name || '',
            contact: newVal.contact || '',
            address: newVal.address || '',
            email: newVal.email || '',
        };
    }
}, { immediate: true });

// Reset on open
watch(() => props.show, (val) => {
    if (val && props.supplier) {
        isEditMode.value = true;
        errors.value = {};
    }
});

// Methods
const handleClose = () => {
    isEditMode.value = false;
    errors.value = {};
    emit('close');
};

const submitForm = () => {
    if (!props.supplier?.id) return;
    isSubmitting.value = true;

    router.put(route('suppliers.update', props.supplier.id), form.value, {
        preserveScroll: true,
        onSuccess: () => {
            emit('updated');
            handleClose();
        },
        onError: (err) => {
            errors.value = err;
        },
        onFinish: () => {
            isSubmitting.value = false;
        },
    });
};
</script>

<template>
    <Modal :show="show" @close="handleClose">
        <div class="p-6 bg-white dark:bg-gray-800 max-w-md w-full rounded-xl animate-fadeIn">
            <h2 class="text-xl font-semibold text-gray-800 dark:text-white text-center mb-6">
                Edit Supplier
            </h2>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <InputLabel for="name" value="Nama Supplier" />
                    <TextInput id="name" v-model="form.name" class="mt-1 block w-full" required />
                    <InputError class="mt-2" :message="errors.name" />
                </div>

                <div>
                    <InputLabel for="contact" value="Kontak" />
                    <TextInput id="contact" v-model="form.contact" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="errors.contact" />
                </div>

                <div>
                    <InputLabel for="address" value="Alamat" />
                    <TextInput id="address" v-model="form.address" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="errors.address" />
                </div>

                <div>
                    <InputLabel for="email" value="Email" />
                    <TextInput id="email" type="email" v-model="form.email" class="mt-1 block w-full" />
                    <InputError class="mt-2" :message="errors.email" />
                </div>

                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700 space-x-3">
                    <SecondaryButton @click="handleClose" :disabled="isSubmitting">
                        Batal
                    </SecondaryButton>
                    <PrimaryButton type="submit" :disabled="isSubmitting" :class="{ 'opacity-25': isSubmitting }">
                        {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
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
