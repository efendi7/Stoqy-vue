<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

// Import komponen modal
import CreateProductAttributesModal from '@/Components/ProductAttributes/CreateProductAttributesModal.vue';
import UpdateProductAttributesModal from '@/Components/ProductAttributes/UpdateProductAttributesModal.vue';
import DeleteProductAttributesModal from '@/Components/ProductAttributes/DeleteProductAttributesModal.vue';

// --- PROPS ---
const props = defineProps({
    productAttributes: {
        type: Object,
        default: () => ({}),
    },
    // --- PERUBAHAN 1: Terima props 'products' dari controller ---
    products: {
        type: Array,
        default: () => [],
    },
    flash: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    }
});

// --- STATE MANAGEMENT ---
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const selectedAttribute = ref(null);
const search = ref(props.filters.search);

// --- METHODS / FUNCTIONS ---
const openCreateModal = () => {
    showCreateModal.value = true;
};

const openUpdateModal = (attribute) => {
    selectedAttribute.value = attribute;
    showUpdateModal.value = true;
};

const openDeleteModal = (attribute) => {
    selectedAttribute.value = attribute;
    showDeleteModal.value = true;
};

const closeModal = () => {
    showCreateModal.value = false;
    showUpdateModal.value = false;
    showDeleteModal.value = false;
    selectedAttribute.value = null;
};

watch(search, debounce((value) => {
    router.get(route('product_attributes.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));
</script>

<template>
    <Head title="Atribut Produk" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Atribut Produk</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola atribut produk Anda</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    Tambah Atribut
                </PrimaryButton>
            </div>
        </template>

        <div class="py-8">
            <div class="container mx-auto px-6">
                
                <div v-if="flash.success" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <TextInput v-model="search" type="text" class="block w-full h-11 pl-10" placeholder="Cari atribut produk..." />
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">ID Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Atribut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nilai Atribut</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-if="productAttributes.data.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada atribut produk ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                
                                <tr v-for="attr in productAttributes.data" :key="attr.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ attr.product_id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attr.product?.name || '-' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attr.attribute_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ attr.attribute_value }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-4">
                                            <button @click="openUpdateModal(attr)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">Edit</button>
                                            <button @click="openDeleteModal(attr)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <AdvancedPagination :pagination="productAttributes" />
            </div>
        </div>

        <CreateProductAttributesModal 
            :show="showCreateModal" 
            :products="props.products"
            @close="closeModal" />

        <UpdateProductAttributesModal 
            :show="showUpdateModal"
            :attribute="selectedAttribute" 
            :products="props.products"
            @close="closeModal" 
        />

        <DeleteProductAttributesModal 
            :show="showDeleteModal" 
            :attribute="selectedAttribute"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>