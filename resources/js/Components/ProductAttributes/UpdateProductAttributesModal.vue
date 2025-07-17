<script setup>
import { ref, computed, watch } from 'vue';
import { router } from '@inertiajs/vue3';
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
    attribute: {
        type: Object,
        default: () => ({}),
    },
});

const emit = defineEmits(['close', 'updated']);

// Reactive state
const isEditMode = ref(false);
const isSubmitting = ref(false);
const errors = ref({});
const originalData = ref({});

// Flash message state
const flashMessage = ref({
    show: false,
    type: '',
    message: ''
});

// Form data
const form = ref({
    product_id: '',
    attribute_name: '',
    attribute_value: '',
});

// Computed properties
const isAdmin = computed(() => {
    // Assuming you have access to auth user data
    // You might need to pass this as a prop or get it from a global store
    return true; // Adjust based on your auth implementation
});

const editButtonText = computed(() => {
    return isEditMode.value ? 'Batal' : 'Edit';
});

const submitButtonText = computed(() => {
    if (isSubmitting.value) return 'Memperbarui...';
    return isEditMode.value ? 'Update' : 'Tutup';
});

const editButtonClass = computed(() => {
    return isEditMode.value
        ? 'bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-700'
        : 'bg-yellow-500 hover:bg-yellow-600 dark:bg-yellow-600 dark:hover:bg-yellow-700';
});

const submitButtonClass = computed(() => {
    return isEditMode.value
        ? 'bg-blue-500 hover:bg-blue-600 dark:bg-blue-600 dark:hover:bg-blue-700'
        : 'bg-gray-500 hover:bg-gray-600 dark:bg-gray-600 dark:hover:bg-gray-700';
});

const flashMessageClass = computed(() => {
    const baseClass = 'mb-4 p-4 rounded-lg border';
    if (flashMessage.value.type === 'success') {
        return `${baseClass} bg-green-100 border-green-400 text-green-700 dark:bg-green-900 dark:bg-opacity-50 dark:border-green-700 dark:text-green-300`;
    } else if (flashMessage.value.type === 'error') {
        return `${baseClass} bg-red-100 border-red-400 text-red-700 dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300`;
    }
    return baseClass;
});

// Watch for attribute changes
watch(() => props.attribute, (newAttribute) => {
    if (newAttribute && Object.keys(newAttribute).length > 0) {
        populateForm(newAttribute);
    }
}, { immediate: true, deep: true });

// Functions
const populateForm = (attribute) => {
    form.value = {
        product_id: attribute.product_id || '',
        attribute_name: attribute.attribute_name || '',
        attribute_value: attribute.attribute_value || '',
    };
};

const saveOriginalData = () => {
    originalData.value = { ...form.value };
};

const restoreOriginalData = () => {
    form.value = { ...originalData.value };
};

const toggleEditMode = () => {
    if (isEditMode.value) {
        // Cancel edit mode
        restoreOriginalData();
        isEditMode.value = false;
        hideErrors();
    } else {
        // Enter edit mode
        saveOriginalData();
        isEditMode.value = true;
        hideErrors();
    }
};

const hideErrors = () => {
    errors.value = {};
};

const showErrors = (errorData) => {
    if (typeof errorData === 'string') {
        errors.value = { general: [errorData] };
    } else if (typeof errorData === 'object' && errorData !== null) {
        errors.value = errorData;
    }
};

const submitForm = async () => {
    if (isSubmitting.value || !props.attribute.id) return;
    
    isSubmitting.value = true;
    hideErrors();

    try {
        // Prepare form data
        const formData = {
            product_id: form.value.product_id,
            attribute_name: form.value.attribute_name,
            attribute_value: form.value.attribute_value,
        };

        // Submit with Inertia
        await router.put(route('product_attributes.update', props.attribute.id), formData, {
            onSuccess: (response) => {
                console.log('Success: Product attribute updated successfully');
                
                // Exit edit mode
                isEditMode.value = false;
                
                // Show success flash message
                showFlashMessage('success', 'Atribut produk berhasil diperbarui!');
                
                // Emit updated event
                emit('updated');
                
                // Close modal after a short delay
                setTimeout(() => {
                    handleClose();
                }, 2000);
            },
            onError: (formErrors) => {
                showErrors(formErrors);
                console.error('Form submission errors:', formErrors);
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    } catch (error) {
        console.error('Submission error:', error);
        showErrors('Terjadi kesalahan tidak dikenal saat memperbarui atribut produk.');
        isSubmitting.value = false;
    }
};

const handleSubmitButtonClick = () => {
    if (isEditMode.value) {
        submitForm();
    } else {
        handleClose();
    }
};

const handleClose = () => {
    // Reset edit mode
    isEditMode.value = false;
    
    // Clear errors
    hideErrors();
    
    // Hide flash message
    hideFlashMessage();
    
    // Reset form to original attribute data
    if (props.attribute && Object.keys(props.attribute).length > 0) {
        populateForm(props.attribute);
    }
    
    // Emit close event
    emit('close');
};

// Flash message functions
const showFlashMessage = (type, message) => {
    flashMessage.value = {
        show: true,
        type: type,
        message: message
    };
};

const hideFlashMessage = () => {
    flashMessage.value = {
        show: false,
        type: '',
        message: ''
    };
};

// Handle ESC key
const handleKeydown = (event) => {
    if (event.key === 'Escape' && props.show) {
        handleClose();
    }
};

// Add/remove event listener for ESC key
watch(() => props.show, (newValue) => {
    if (newValue) {
        document.addEventListener('keydown', handleKeydown);
    } else {
        document.removeEventListener('keydown', handleKeydown);
    }
});
</script>

<template>
    <Modal :show="show" @close="handleClose">
        <div class="p-6 bg-white dark:bg-gray-800 max-w-2xl mx-auto rounded-lg">
            <!-- HEADER -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Atribut Produk</h2>
                <button 
                    @click="handleClose"
                    class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white text-2xl font-bold"
                >
                    &times;
                </button>
            </div>

            <!-- Flash Message -->
            <div 
                v-if="flashMessage.show" 
                :class="flashMessageClass"
            >
                <div class="flex items-center">
                    <svg 
                        v-if="flashMessage.type === 'success'" 
                        class="w-5 h-5 mr-2" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <svg 
                        v-else-if="flashMessage.type === 'error'" 
                        class="w-5 h-5 mr-2" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ flashMessage.message }}</span>
                </div>
            </div>

            <!-- Error Messages -->
            <div 
                v-if="Object.keys(errors).length > 0" 
                class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300"
            >
                <ul class="list-disc pl-5">
                    <template v-for="(fieldErrors, field) in errors" :key="field">
                        <li v-for="error in (Array.isArray(fieldErrors) ? fieldErrors : [fieldErrors])" :key="error">
                            {{ error }}
                        </li>
                    </template>
                </ul>
            </div>

            <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Form Fields -->
                <div class="space-y-4">
                    <div>
                        <InputLabel for="product_id_detail" value="ID Produk" class="text-gray-900 dark:text-gray-100" />
                        <TextInput 
                            id="product_id_detail" 
                            type="text" 
                            class="mt-1 block w-full" 
                            v-model="form.product_id" 
                            :readonly="!isEditMode"
                            required 
                        />
                        <InputError class="mt-2" :message="errors.product_id?.[0]" />
                    </div>

                    <div>
                        <InputLabel for="attribute_name_detail" value="Nama Atribut" class="text-gray-900 dark:text-gray-100" />
                        <TextInput 
                            id="attribute_name_detail" 
                            type="text" 
                            class="mt-1 block w-full" 
                            v-model="form.attribute_name" 
                            :readonly="!isEditMode"
                            required 
                        />
                        <InputError class="mt-2" :message="errors.attribute_name?.[0]" />
                    </div>

                    <div>
                        <InputLabel for="attribute_value_detail" value="Nilai Atribut" class="text-gray-900 dark:text-gray-100" />
                        <TextInput 
                            id="attribute_value_detail" 
                            type="text" 
                            class="mt-1 block w-full" 
                            v-model="form.attribute_value" 
                            :readonly="!isEditMode"
                            required 
                        />
                        <InputError class="mt-2" :message="errors.attribute_value?.[0]" />
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button 
                        v-if="isAdmin"
                        type="button" 
                        @click="toggleEditMode"
                        :class="editButtonClass"
                        class="text-xs font-semibold uppercase tracking-widest text-white py-2 px-6 rounded-lg transition-all transform hover:scale-105"
                        :disabled="isSubmitting"
                    >
                        {{ editButtonText }}
                    </button>
                    
                    <button 
                        type="button" 
                        @click="handleSubmitButtonClick"
                        :class="submitButtonClass"
                        class="text-xs font-semibold uppercase tracking-widest text-white py-2 px-6 rounded-lg transition-all transform hover:scale-105"
                        :disabled="isSubmitting"
                    >
                        {{ submitButtonText }}
                    </button>
                </div>
            </form>
        </div>
    </Modal>
</template>