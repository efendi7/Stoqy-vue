<script setup>
import { ref, computed, watch, nextTick } from 'vue';
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
    product: {
        type: Object,
        default: () => ({}),
    },
    categories: {
        type: Array,
        default: () => [],
    },
    suppliers: {
        type: Array,
        default: () => [],
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
    name: '',
    sku: '',
    category_id: '',
    supplier_id: '',
    purchase_price: '',
    sale_price: '',
    initial_stock: '',
    stock: '',
    minimum_stock: '',
    image: null,
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

// Watch for product changes
watch(() => props.product, (newProduct) => {
    if (newProduct && Object.keys(newProduct).length > 0) {
        populateForm(newProduct);
    }
}, { immediate: true, deep: true });

// Watch for stock changes to sync with initial_stock in edit mode
watch(() => form.value.stock, (newStockValue) => {
    if (isEditMode.value && newStockValue !== null && newStockValue !== undefined) {
        form.value.initial_stock = newStockValue;
    }
});

// Functions
const populateForm = (product) => {
    form.value = {
        name: product.name || '',
        sku: product.sku || '',
        category_id: product.category_id || '',
        supplier_id: product.supplier_id || '',
        purchase_price: product.purchase_price || '',
        sale_price: product.sale_price || '',
        initial_stock: product.initial_stock || '',
        stock: product.stock || '',
        minimum_stock: product.minimum_stock || '',
        image: null,
    };
};

const saveOriginalData = () => {
    originalData.value = { ...form.value };
};

const restoreOriginalData = () => {
    form.value = { ...originalData.value };
    form.value.image = null; // Reset file input
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

const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            errors.value.image = ['Ukuran file maksimal 2MB'];
            event.target.value = '';
            return;
        }
        
        // Validate file type
        const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            errors.value.image = ['Format file harus JPG, JPEG, PNG, atau GIF'];
            event.target.value = '';
            return;
        }
        
        form.value.image = file;
        
        // Clear previous error
        if (errors.value.image) {
            delete errors.value.image;
        }
    }
};

const submitForm = async () => {
    if (isSubmitting.value || !props.product.id) return;
    
    isSubmitting.value = true;
    hideErrors();

    try {
        // Prepare form data
        const formData = new FormData();
        
        // Add all form fields
        Object.keys(form.value).forEach(key => {
            if (key === 'image' && form.value[key]) {
                formData.append(key, form.value[key]);
            } else if (key !== 'image' && form.value[key] !== null && form.value[key] !== undefined) {
                formData.append(key, form.value[key]);
            }
        });

        // Add method spoofing for PUT request
        formData.append('_method', 'PUT');

        // Submit with Inertia
        await router.post(route('products.update', props.product.id), formData, {
            forceFormData: true,
            onSuccess: (response) => {
                console.log('Success: Product updated successfully');
                
                // Exit edit mode
                isEditMode.value = false;
                
                // Show success flash message
                showFlashMessage('success', 'Produk berhasil diperbarui!');
                
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
        showErrors('Terjadi kesalahan tidak dikenal saat memperbarui produk.');
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
    
    // Reset form to original product data
    if (props.product && Object.keys(props.product).length > 0) {
        populateForm(props.product);
    }
    
    // Reset file input
    const fileInput = document.getElementById('image_detail');
    if (fileInput) {
        fileInput.value = '';
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
        <div class="p-6 bg-white dark:bg-gray-800 max-w-4xl mx-auto rounded-lg">
            <!-- HEADER -->
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Detail Produk</h2>
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
                <!-- Grid Layout dengan 2 kolom -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Kolom Kiri -->
                    <div class="space-y-4">
                        <div>
                            <InputLabel for="name_detail" value="Nama Produk" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="name_detail" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.name" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.name?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="sku_detail" value="SKU" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="sku_detail" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.sku" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.sku?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="category_id_detail" value="Kategori" class="text-gray-900 dark:text-gray-100" />
                            <select 
                                id="category_id_detail" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                v-model="form.category_id" 
                                :disabled="!isEditMode"
                                required
                            >
                                <option value="">Pilih Kategori</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="errors.category_id?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="supplier_id_detail" value="Supplier" class="text-gray-900 dark:text-gray-100" />
                            <select 
                                id="supplier_id_detail" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                v-model="form.supplier_id"
                                :disabled="!isEditMode"
                            >
                                <option value="">Pilih Supplier</option>
                                <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="errors.supplier_id?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="purchase_price_detail" value="Harga Beli" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="purchase_price_detail" 
                                type="number" 
                                step="100" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.purchase_price" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.purchase_price?.[0]" />
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <InputLabel for="sale_price_detail" value="Harga Jual" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="sale_price_detail" 
                                type="number" 
                                step="100" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.sale_price" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.sale_price?.[0]" />
                        </div>

                        <div v-if="false">
                            <InputLabel for="initial_stock_detail" value="Stok Awal" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="initial_stock_detail" 
                                type="number" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.initial_stock" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.initial_stock?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="stock_detail" value="Stok Sekarang" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="stock_detail" 
                                type="number" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.stock" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.stock?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="minimum_stock_detail" value="Minimum Stok" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="minimum_stock_detail" 
                                type="number" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.minimum_stock" 
                                :readonly="!isEditMode"
                                required 
                            />
                            <InputError class="mt-2" :message="errors.minimum_stock?.[0]" />
                        </div>
                        

                        <div>
                            <InputLabel for="image_detail" value="Gambar Produk" class="text-gray-900 dark:text-gray-100" />
                            
                            <!-- Current Image Display -->
                            <div class="mt-2 mb-2">
                                <img 
                                    v-if="product.image" 
                                    :src="`/storage/${product.image}`" 
                                    alt="Product Image" 
                                    class="w-32 h-32 object-cover rounded-lg"
                                />
                                <p v-else class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada gambar</p>
                            </div>

                            <!-- File Input (only shown in edit mode) -->
                            <input 
                                v-if="isEditMode"
                                type="file" 
                                id="image_detail"
                                @change="handleFileChange"
                                accept="image/jpeg,image/jpg,image/png,image/gif"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800"
                            />
                            <InputError class="mt-2" :message="errors.image?.[0]" />
                        </div>
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