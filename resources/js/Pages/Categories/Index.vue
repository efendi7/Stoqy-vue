<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

// Import komponen modal yang baru
import CreateCategoriesModal from '@/Components/Categories/CreateCategoriesModal.vue';
import UpdateCategoriesModal from '@/Components/Categories/UpdateCategoriesModal.vue';
import DeleteCategoriesModal from '@/Components/Categories/DeleteCategoriesModal.vue';

// --- PROPS ---
const props = defineProps({
    categories: {
        type: Object,
        default: () => ({}),
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
// State untuk mengontrol tampilan modal
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const selectedCategory = ref(null);

// State untuk fitur pencarian
const search = ref(props.filters.search);

// --- METHODS / FUNCTIONS ---
// Membuka modal tambah data
const openCreateModal = () => {
    showCreateModal.value = true;
};

// Membuka modal untuk mengubah data
const openUpdateModal = (category) => {
    selectedCategory.value = category;
    showUpdateModal.value = true;
};

// Membuka modal untuk menghapus data
const openDeleteModal = (category) => {
    selectedCategory.value = category;
    showDeleteModal.value = true;
};

// Menutup semua modal
const closeModal = () => {
    showCreateModal.value = false;
    showUpdateModal.value = false;
    showDeleteModal.value = false;
    selectedCategory.value = null; // Reset selection
};

// Watcher untuk fitur pencarian dengan debounce
watch(search, debounce((value) => {
    router.get(route('categories.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

</script>

<template>
    <Head title="Kategori" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Kategori</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola kategori produk Anda</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    Tambah Kategori
                </PrimaryButton>
            </div>
        </template>

        <div class="py-8">
            <div class="container mx-auto px-6">
                
                <div v-if="flash.success" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</p>
                    </div>
                </div>

                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <TextInput v-model="search" type="text" class="block w-full h-11 pl-10" placeholder="Cari kategori..." />
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Deskripsi</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-if="categories.data.length === 0">
                                    <td colspan="3" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2V5a2 2 0 012-2h2zm0 0l-2.5 2.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada kategori ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="category in categories.data" :key="category.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ category.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ category.description }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-4">
                                            <button @click="openUpdateModal(category)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">Edit</button>
                                            <button @click="openDeleteModal(category)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <AdvancedPagination :pagination="categories" />
            </div>
        </div>

        <CreateCategoriesModal :show="showCreateModal" @close="closeModal" />

        <UpdateCategoriesModal 
            :show="showUpdateModal"
            :category="selectedCategory" 
            @close="closeModal" 
        />

        <DeleteCategoriesModal 
            :show="showDeleteModal" 
            :category="selectedCategory"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>