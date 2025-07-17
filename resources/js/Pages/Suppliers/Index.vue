<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

// Modal Supplier (Anda buat sendiri seperti di kategori)
import CreateSupplierModal from '@/Components/Suppliers/CreateSupplierModal.vue';
import UpdateSupplierModal from '@/Components/Suppliers/UpdateSupplierModal.vue';
import DeleteSupplierModal from '@/Components/Suppliers/DeleteSupplierModal.vue';

const props = defineProps({
  suppliers: Object,
  flash: Object,
  filters: {
    type: Object,
    default: () => ({}),
  },
  auth: Object,
});


const search = ref(props.filters.search || '');
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const selectedSupplier = ref(null);

const openCreateModal = () => showCreateModal.value = true;
const openUpdateModal = (supplier) => {
  selectedSupplier.value = supplier;
  showUpdateModal.value = true;
};
const openDeleteModal = (supplier) => {
  selectedSupplier.value = supplier;
  showDeleteModal.value = true;
};
const closeModal = () => {
  showCreateModal.value = false;
  showUpdateModal.value = false;
  showDeleteModal.value = false;
  selectedSupplier.value = null;
};

watch(search, debounce((value) => {
  router.get(route('suppliers.index'), { search: value }, { preserveState: true, replace: true });
}, 300));
</script>

<template>
  <Head title="Supplier" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Supplier</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola data penyuplai</p>
        </div>
        <PrimaryButton v-if="auth.user.role !== 'warehouse_manager' && auth.user.role !== 'warehouse_staff'" @click="openCreateModal">
          Tambah Supplier
        </PrimaryButton>
      </div>
    </template>

    <div class="py-8">
      <div class="container mx-auto px-6">

        <!-- Flash Message -->
        <div v-if="flash.success" class="mb-4 bg-green-100 border border-green-300 text-green-800 p-4 rounded">
          {{ flash.success }}
        </div>
        <div v-if="flash.error" class="mb-4 bg-red-100 border border-red-300 text-red-800 p-4 rounded">
          {{ flash.error }}
        </div>

        <!-- Search -->
        <div class="mb-6 max-w-md relative">
          <div class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
          <TextInput v-model="search" class="pl-10 w-full h-11" placeholder="Cari berdasarkan nama atau kontak" />
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-900">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kontak</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Alamat</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-if="suppliers.data.length === 0">
                <td colspan="5" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">Tidak ada data supplier</td>
              </tr>
              <tr v-for="supplier in suppliers.data" :key="supplier.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ supplier.name }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ supplier.contact }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ supplier.address }}</td>
                <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ supplier.email }}</td>
                <td class="px-6 py-4 text-sm text-center">
                  <div class="flex justify-center gap-4">
                    <button v-if="auth.user.role !== 'warehouse_manager' && auth.user.role !== 'warehouse_staff'" @click="openUpdateModal(supplier)" class="text-yellow-600 hover:text-yellow-800 font-medium">Edit</button>
                    <button v-if="auth.user.role !== 'warehouse_manager' && auth.user.role !== 'warehouse_staff'" @click="openDeleteModal(supplier)" class="text-red-600 hover:text-red-800 font-medium">Hapus</button>
                    <span v-else class="text-gray-400">Tidak ada akses</span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
          <AdvancedPagination :pagination="suppliers" />
        </div>
      </div>
    </div>

    <!-- Modals -->
    <CreateSupplierModal :show="showCreateModal" @close="closeModal" />
    <UpdateSupplierModal :show="showUpdateModal" :supplier="selectedSupplier" @close="closeModal" />
    <DeleteSupplierModal :show="showDeleteModal" :supplier="selectedSupplier" @close="closeModal" />
  </AuthenticatedLayout>
</template>
