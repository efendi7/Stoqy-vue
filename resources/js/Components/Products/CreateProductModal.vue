<script setup>
import { watch, ref } from 'vue';
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
    form: {
        type: Object,
        required: true,
    },
    categories: {
        type: Array,
        required: true,
    },
    suppliers: {
        type: Array,
        required: true,
    },
});

const emit = defineEmits(['close', 'submit']);

// Reactive state untuk loading dan error
const isSubmitting = ref(false);
const errors = ref({});

// Watch untuk sinkronisasi stock dengan initial_stock
watch(() => props.form.stock, (newStockValue) => {
    if (newStockValue !== null && newStockValue !== undefined) {
        props.form.initial_stock = newStockValue;
    }
});

// Function untuk submit form
const submitForm = async () => {
    if (isSubmitting.value) return;
    
    isSubmitting.value = true;
    errors.value = {};

    try {
        // Pastikan semua nilai numerik adalah number
        const formData = { ...props.form };
        
        // Konversi string ke number untuk field numerik
        if (formData.purchase_price) formData.purchase_price = Number(formData.purchase_price);
        if (formData.sale_price) formData.sale_price = Number(formData.sale_price);
        if (formData.stock) formData.stock = Number(formData.stock);
        if (formData.minimum_stock) formData.minimum_stock = Number(formData.minimum_stock);
        if (formData.category_id) formData.category_id = Number(formData.category_id);
        if (formData.supplier_id) formData.supplier_id = Number(formData.supplier_id);

        // Submit dengan Inertia
        await router.post(route('products.store'), formData, {
            onSuccess: () => {
                console.log('Success: Product created successfully');
                
                // Reset form
                resetForm();
                
                // Emit close event
                emit('close');
                
                // Optional: Emit submit event for parent component
                emit('submit');
            },
            onError: (formErrors) => {
                errors.value = formErrors;
                console.error('Form submission errors:', formErrors);
            },
            onFinish: () => {
                isSubmitting.value = false;
            }
        });
    } catch (error) {
        console.error('Submission error:', error);
        isSubmitting.value = false;
    }
};

// Function untuk reset form
const resetForm = () => {
    Object.keys(props.form).forEach(key => {
        if (key !== 'errors' && key !== 'processing') {
            if (typeof props.form[key] === 'string') {
                props.form[key] = '';
            } else if (typeof props.form[key] === 'number') {
                props.form[key] = key === 'minimum_stock' ? 5 : 0;
            } else if (props.form[key] === null || props.form[key] === undefined) {
                props.form[key] = null;
            }
        }
    });
};

// Handle file input change
const handleFileChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        // Validate file size
        if (file.size > 2 * 1024 * 1024) { // 2MB
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
        
        props.form.image = file;
        
        // Clear previous error
        if (errors.value.image) {
            delete errors.value.image;
        }
    }
};

// Handle modal close
const handleClose = () => {
    resetForm();
    errors.value = {};
    emit('close');
};
</script>

<template>
   <Modal :show="show" @close="handleClose">
    <div class="p-6 bg-white dark:bg-gray-800 max-w-4xl mx-auto">

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                Tambah Produk Baru
            </h2>

            <!-- Error Messages -->
            <div v-if="Object.keys(errors).length > 0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 dark:bg-red-900 dark:bg-opacity-50 dark:border-red-700 dark:text-red-300">
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
                            <InputLabel for="name" value="Nama Produk" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="name" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.name" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.name || errors.name?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="sku" value="SKU" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="sku" 
                                type="text" 
                                class="mt-1 block w-full" 
                                v-model="form.sku" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.sku || errors.sku?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="category" value="Kategori" class="text-gray-900 dark:text-gray-100" />
                            <select 
                                id="category" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                v-model="form.category_id" 
                                required
                            >
                                <option value="">Pilih Kategori</option>
                                <option v-for="cat in categories" :key="cat.id" :value="cat.id">{{ cat.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors?.category_id || errors.category_id?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="supplier" value="Supplier" class="text-gray-900 dark:text-gray-100" />
                            <select 
                                id="supplier" 
                                class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" 
                                v-model="form.supplier_id"
                            >
                                <option value="">Pilih Supplier</option>
                                <option v-for="sup in suppliers" :key="sup.id" :value="sup.id">{{ sup.name }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors?.supplier_id || errors.supplier_id?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="purchase_price" value="Harga Beli" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="purchase_price" 
                                type="number" 
                                step="100" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.purchase_price" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.purchase_price || errors.purchase_price?.[0]" />
                        </div>
                    </div>

                    <!-- Kolom Kanan -->
                    <div class="space-y-4">
                        <div>
                            <InputLabel for="sale_price" value="Harga Jual" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="sale_price" 
                                type="number" 
                                step="100" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.sale_price" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.sale_price || errors.sale_price?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="stock" value="Stok Realtime" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="stock" 
                                type="number" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.stock" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.stock || errors.stock?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="minimum_stock" value="Minimum Stok" class="text-gray-900 dark:text-gray-100" />
                            <TextInput 
                                id="minimum_stock" 
                                type="number" 
                                min="0" 
                                class="mt-1 block w-full" 
                                v-model="form.minimum_stock" 
                                required 
                            />
                            <InputError class="mt-2" :message="form.errors?.minimum_stock || errors.minimum_stock?.[0]" />
                        </div>

                        <div>
                            <InputLabel for="image" value="Gambar Produk" class="text-gray-900 dark:text-gray-100" />
                            <input 
                                type="file" 
                                id="image"
                                @change="handleFileChange"
                                accept="image/jpeg,image/jpg,image/png,image/gif"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-800"
                            />
                            <div v-if="form.progress" class="mt-2">
                                <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Uploading...</span>
                                    <span>{{ form.progress.percentage }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                    <div 
                                        class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                                        :style="{ width: form.progress.percentage + '%' }"
                                    ></div>
                                </div>
                            </div>
                            <InputError class="mt-2" :message="form.errors?.image || errors.image?.[0]" />
                        </div>
                    </div>
                </div>

                <!-- Footer Buttons -->
                <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                    <SecondaryButton @click="handleClose" :disabled="isSubmitting">
                        Batal
                    </SecondaryButton>
                    <PrimaryButton 
                        type="submit" 
                        class="ml-4" 
                        :class="{ 'opacity-25': isSubmitting }" 
                        :disabled="isSubmitting"
                    >
                        {{ isSubmitting ? 'Menyimpan...' : 'Simpan' }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>