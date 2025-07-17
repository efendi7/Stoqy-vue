<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Modal from '@/Components/Modal.vue';
import CreateProductModal from '@/Components/Products/CreateProductModal.vue';
import UpdateProductModal from '@/Components/Products/UpdateProductModal.vue';
import DeleteProductModal from '@/Components/Products/DeleteProductModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import Scrollbar from '@/Components/Scrollbar.vue'; 

// --- PROPS ---
const props = defineProps({
    products: Object,
    categories: Array,
    suppliers: Array,
    filters: Object,
    auth: Object,
});

// --- STATE UNTUK FLASH MESSAGE ---
const page = usePage();
const displayFlash = ref({ show: false, type: '', message: '' });

// --- WATCHER UNTUK FLASH MESSAGE DARI INERTIA ---
watch(() => page.props.flash, (newFlash) => {
    if (newFlash && (newFlash.success || newFlash.error)) {
        displayFlash.value.show = true;
        displayFlash.value.type = newFlash.success ? 'success' : 'error';
        displayFlash.value.message = newFlash.success || newFlash.error;
    }
}, { immediate: true });

// --- FUNGSI UNTUK MENUTUP FLASH MESSAGE ---
const closeFlashMessage = () => {
    displayFlash.value.show = false;
};

// --- STATES ---
const isCreateModalVisible = ref(false);
const isUpdateModalVisible = ref(false);
const isDeleteModalVisible = ref(false);
const selectedProduct = ref({});
const productToDelete = ref({});
const search = ref(props.filters?.search || '');
const status = ref(props.filters?.status || '');

// --- STATE UNTUK IMAGE LOADING ---
const imageLoadingErrors = ref(new Set());
const imageLoadingStates = ref(new Map());

// --- FORMS ---
const createForm = useForm({
    name: '',
    sku: '',
    category_id: '',
    supplier_id: '',
    purchase_price: 0,
    sale_price: 0,
    stock: 0,
    minimum_stock: 5,
    initial_stock: 0,
    image: null,
});

// --- FUNCTIONS ---
const openCreateModal = () => {
    createForm.reset();
    isCreateModalVisible.value = true;
};

const openUpdateModal = (product) => {
    selectedProduct.value = { ...product };
    isUpdateModalVisible.value = true;
};

const openDeleteModal = (product) => {
    productToDelete.value = { ...product };
    isDeleteModalVisible.value = true;
};

const closeCreateModal = () => {
    isCreateModalVisible.value = false;
    createForm.reset();
};

const closeUpdateModal = () => {
    isUpdateModalVisible.value = false;
    selectedProduct.value = {};
};

const closeDeleteModal = () => {
    isDeleteModalVisible.value = false;
    productToDelete.value = {};
};

const handleProductUpdated = () => {
    router.reload({ only: ['products'] });
    console.log('Product updated successfully');
};

const handleProductDeleted = () => {
    router.reload({ only: ['products'] });
    console.log('Product deleted successfully');
};

// Fungsi lama untuk fallback jika diperlukan
const deleteProduct = (product) => {
    if (confirm(`Yakin ingin menghapus produk "${product.name}"?`)) {
        router.delete(route('products.destroy', product.id), {
            preserveScroll: true,
        });
    }
};

watch([search, status], debounce(function ([searchValue, statusValue]) {
    router.get(route('products.index'), {
        search: searchValue,
        status: statusValue,
    }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const getStatusClass = (product) => {
    if (product.stock == 0) return 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400';
    if (product.stock < product.minimum_stock) return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400';
    return 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400';
};

// --- IMAGE HANDLING FUNCTIONS ---
const handleImageLoad = (productId) => {
    imageLoadingStates.value.set(productId, 'loaded');
    imageLoadingErrors.value.delete(productId);
};

const handleImageError = (productId) => {
    imageLoadingErrors.value.add(productId);
    imageLoadingStates.value.set(productId, 'error');
};

const handleImageLoading = (productId) => {
    imageLoadingStates.value.set(productId, 'loading');
};

const getImageUrl = (product) => {
    // Prioritas URL gambar
    if (product.image_url) return product.image_url;
    if (product.image) return product.image;
    return null;
};

const shouldShowImage = (product) => {
    const imageUrl = getImageUrl(product);
    return imageUrl && !imageLoadingErrors.value.has(product.id);
};

const getPlaceholderText = (productName) => {
    // Ambil 2 huruf pertama dari nama produk untuk placeholder
    return productName ? productName.slice(0, 2).toUpperCase() : 'PR';
};
</script>

<template>
    <Head title="Manajemen Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Produk</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola inventaris produk Anda.</p>
                </div>
                <div v-if="auth.user.role === 'admin'" class="flex items-center space-x-3">
                    <Link :href="route('products.import-export.index')" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                        Import/Export
                    </Link>
                    <PrimaryButton @click="openCreateModal">Tambah Produk</PrimaryButton>
                </div>
            </div>
        </template>

        <div class="py-12 pb-28">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                
                <FlashMessage 
                    v-if="displayFlash.show"
                    :type="displayFlash.type"
                    :message="displayFlash.message"
                    @close="closeFlashMessage"
                    class="mb-4"
                />

                <div class="mb-6 flex flex-col md:flex-row gap-4">
                    <TextInput 
                        type="text" 
                        v-model="search" 
                        class="w-full md:w-1/2" 
                        placeholder="Cari nama atau SKU..." 
                    />
                    <select 
                        v-model="status" 
                        class="w-full md:w-auto border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                    >
                        <option value="">Semua Status</option>
                        <option value="Tersedia">Tersedia</option>
                        <option value="Warning">Stok Rendah</option>
                        <option value="Habis">Habis</option>
                    </select>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                  
                         <Scrollbar>
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Supplier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Harga</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Stok</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Status</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <!-- Image Container -->
                                            <div class="flex-shrink-0 mr-4">
                                                <!-- Show image if available and not errored -->
                                                <div v-if="shouldShowImage(product)" class="relative">
                                                    <img 
                                                        :src="getImageUrl(product)" 
                                                        :alt="`Gambar ${product.name}`"
                                                        class="w-12 h-12 rounded-lg object-cover border border-gray-200 dark:border-gray-600 shadow-sm"
                                                        :class="{ 'opacity-50': imageLoadingStates.get(product.id) === 'loading' }"
                                                        @load="handleImageLoad(product.id)"
                                                        @error="handleImageError(product.id)"
                                                        @loadstart="handleImageLoading(product.id)"
                                                    />
                                                    <!-- Loading spinner overlay -->
                                                    <div v-if="imageLoadingStates.get(product.id) === 'loading'" 
                                                         class="absolute inset-0 flex items-center justify-center bg-gray-100 dark:bg-gray-700 rounded-lg">
                                                        <div class="animate-spin rounded-full h-4 w-4 border-2 border-indigo-500 border-t-transparent"></div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Placeholder if no image or image failed to load -->
                                                <div v-else class="w-12 h-12 rounded-lg bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-800 dark:to-indigo-900 flex items-center justify-center border border-gray-200 dark:border-gray-600">
                                                    <span class="text-xs font-semibold text-indigo-600 dark:text-indigo-300">
                                                        {{ getPlaceholderText(product.name) }}
                                                    </span>
                                                </div>
                                            </div>
                                            
                                            <!-- Product Info -->
                                            <div class="min-w-0 flex-1">
                                                <div class="font-medium text-gray-900 dark:text-gray-100 truncate">
                                                    {{ product.name }}
                                                </div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400 truncate">
                                                    {{ product.category?.name || 'Tanpa Kategori' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900 dark:text-gray-100 font-mono">
                                            {{ product.sku }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ product.supplier?.name || '-' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            <div class="mb-1">
                                                <span class="text-xs text-gray-400">Beli:</span>
                                                <span class="ml-1 font-medium">Rp {{ Number(product.purchase_price).toLocaleString('id-ID') }}</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-400">Jual:</span>
                                                <span class="ml-1 font-medium text-green-600 dark:text-green-400">Rp {{ Number(product.sale_price).toLocaleString('id-ID') }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ product.stock }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            Min: {{ product.minimum_stock }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full" :class="getStatusClass(product)">
                                            {{ 
                                                product.stock == 0 
                                                    ? 'Habis' 
                                                    : product.stock < product.minimum_stock 
                                                        ? 'Menipis' 
                                                        : 'Tersedia' 
                                            }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex items-center justify-center space-x-2">
                                            <button 
                                                @click="openUpdateModal(product)" 
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm font-medium transition-colors"
                                            >
                                                Detail
                                            </button>
                                            <span class="text-gray-300 dark:text-gray-600">|</span>
                                            <button 
                                                @click="openDeleteModal(product)" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm font-medium transition-colors"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-if="products.data.length === 0">
                                    <td colspan="7" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center justify-center">
                                            <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2M4 13h2m13-8l-4 4m0 0l-4-4m4 4V3"></path>
                                                </svg>
                                            </div>
                                            <div class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                                                Tidak ada produk
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                                Data produk tidak ditemukan dengan filter yang diterapkan.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        </Scrollbar>
                   
                </div>

                <AdvancedPagination :pagination="products" />
            </div>
        </div>

        <CreateProductModal 
            :show="isCreateModalVisible" 
            @close="closeCreateModal"
            :form="createForm"
            :categories="categories"
            :suppliers="suppliers"
        />

        <UpdateProductModal 
            :show="isUpdateModalVisible" 
            :product="selectedProduct"
            :categories="categories"
            :suppliers="suppliers"
            @close="closeUpdateModal"
            @updated="handleProductUpdated"
        />

        <DeleteProductModal 
            :show="isDeleteModalVisible" 
            :product="productToDelete"
            @close="closeDeleteModal"
            @deleted="handleProductDeleted"
        />
    </AuthenticatedLayout>
</template>