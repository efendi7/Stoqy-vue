<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';
import FlashMessage from '@/Components/FlashMessage.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { debounce } from 'lodash';

// Import komponen modal yang baru
import CreateStockTransactionModal from '@/Components/StockTransactions/CreateStockTransactionModal.vue';
import UpdateStockTransactionModal from '@/Components/StockTransactions/UpdateStockTransactionModal.vue';
import DeleteStockTransactionModal from '@/Components/StockTransactions/DeleteStockTransactionModal.vue';

// --- PROPS ---
const props = defineProps({
    transactions: {
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
    },
    userRole: {
        type: String,
        default: 'warehouse_staff',
    },
    auth: {
        type: Object,
        default: () => ({}),
    },
     products: {
        type: Object,
        default: () => ({}),
    },
    users: {
        type: Object,
        default: () => ({}),
    },
});

// --- STATE MANAGEMENT ---
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const selectedTransaction = ref(null);
const search = ref(props.filters.search || '');

// State lokal untuk flash message agar bisa ditutup
const successMessage = ref('');
const errorMessage = ref('');

// Watcher untuk menyinkronkan flash props ke state lokal
watch(() => props.flash, (newFlash) => {
    successMessage.value = newFlash.success || '';
    errorMessage.value = newFlash.error || '';
}, { immediate: true });


// --- METHODS / FUNCTIONS ---
const openCreateModal = () => {
    showCreateModal.value = true;
};

const openUpdateModal = (transaction) => {
    selectedTransaction.value = transaction;
    showUpdateModal.value = true;
};

const openDeleteModal = (transaction) => {
    selectedTransaction.value = transaction;
    showDeleteModal.value = true;
};

const closeModal = () => {
    showCreateModal.value = false;
    showUpdateModal.value = false;
    showDeleteModal.value = false;
    selectedTransaction.value = null;
};

watch(search, debounce((value) => {
    router.get(route('stock_transactions.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

const updateStatus = (transaction, status) => {
    router.patch(route('stock_transactions.update_status', transaction.id), {
        status: status
    }, {
        preserveState: true,
    });
};

const goToConfirmedTransactions = () => {
    router.get(route('stock_transactions.confirmed'));
};

const goToPendingTransactions = () => {
    router.get(route('stock_transactions.pending'));
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
};

const getStatusBadgeClass = (status) => {
    const classes = 'px-3 py-1 rounded-md text-xs font-medium';
    switch (status) {
        case 'Pending':
            return `${classes} bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400`;
        case 'Confirmed':
            return `${classes} bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400`;
        case 'Diterima':
            return `${classes} bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400`;
        case 'Ditolak':
            return `${classes} bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400`;
        default:
            return `${classes} bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400`;
    }
};

const getTypeBadgeClass = (type) => {
    const classes = 'px-2 py-1 rounded text-xs font-medium';
    return type === 'Masuk' 
        ? `${classes} bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400`
        : `${classes} bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400`;
};

</script>

<template>
    <Head title="Daftar Transaksi Stok" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Daftar Transaksi Stok</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola transaksi stok barang Anda</p>
                </div>
                <div class="flex items-center space-x-4">
                    <PrimaryButton 
                        v-if="auth.user.role === 'warehouse_manager'"
                        @click="openCreateModal"
                        class="bg-green-600 hover:bg-green-700"
                    >
                        Tambah Transaksi
                    </PrimaryButton>
                    
                    <button 
                        v-if="auth.user.role === 'warehouse_manager'"
                        @click="goToConfirmedTransactions"
                        class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-300 font-medium"
                    >
                        Lihat Semua Transaksi Dikonfirmasi
                    </button>
                    
                    <button 
                        v-if="auth.user.role === 'warehouse_staff'"
                        @click="goToPendingTransactions"
                        class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                    >
                        Lihat Transaksi Pending
                    </button>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="container mx-auto px-6">
                
                <!-- Flash Messages -->
                <FlashMessage 
                    v-if="successMessage" 
                    type="success" 
                    :message="successMessage" 
                    class="mb-6"
                    @close="successMessage = ''"
                />
                <FlashMessage 
                    v-if="errorMessage" 
                    type="error" 
                    :message="errorMessage" 
                    class="mb-6"
                    @close="errorMessage = ''"
                />

                <!-- Search Bar -->
                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <TextInput 
                            v-model="search" 
                            type="text" 
                            class="block w-full h-11 pl-10" 
                            placeholder="Cari berdasarkan produk atau jenis..." 
                        />
                    </div>
                </div>

                <!-- Keterangan untuk warehouse_manager -->
                <div v-if="auth.user.role === 'warehouse_manager'" class="mb-6">
                    <div class="bg-white dark:bg-gray-800 shadow-md p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="font-semibold text-center text-yellow-700 dark:text-yellow-400 mb-3">Keterangan: Status Pending</div>
                        <div class="flex items-center justify-center space-x-8">
                            <div class="flex items-center space-x-2">
                                <span class="w-4 h-4 bg-white border border-gray-400 inline-block rounded"></span>
                                <span class="text-gray-600 dark:text-gray-300 text-sm">Stok telah dikonfirmasi oleh staff</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="w-4 h-4 bg-yellow-400 border border-gray-400 inline-block rounded"></span>
                                <span class="text-gray-600 dark:text-gray-300 text-sm">Menunggu konfirmasi pemeriksaan staff</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Transaksi -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Produk</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Jenis</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Kuantitas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Catatan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tanggal</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-if="transactions.data.length === 0">
                                    <td colspan="8" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada transaksi stok ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="transaction in transactions.data" :key="transaction.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                        {{ transaction.product?.name || 'Produk Tidak Ditemukan' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ transaction.user?.name || 'User Tidak Ditemukan' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <span :class="getTypeBadgeClass(transaction.type)">
                                            {{ transaction.type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center text-gray-900 dark:text-white">
                                        {{ transaction.quantity }}
                                    </td>
                                  <td class="px-6 py-4 whitespace-nowrap text-sm">
    <select 
        v-if="auth.user.role === 'warehouse_manager'"
        @change="updateStatus(transaction, $event.target.value)"
        :value="transaction.status"
        class="border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-1 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-500 transition-all"
    >
        <template v-if="transaction.status === 'Confirmed'">
            <option value="Confirmed">Confirmed</option>
            <option value="Diterima">Diterima</option>
            <option value="Ditolak">Ditolak</option>
        </template>
        
        <template v-else>
            <option value="Pending">Pending</option>
            <option value="Diterima">Diterima</option>
            <option value="Ditolak">Ditolak</option>
        </template>
    </select>
    
    <span v-else :class="getStatusBadgeClass(transaction.status)">
        {{ transaction.status }}
    </span>
</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ transaction.notes || '-' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                        {{ formatDate(transaction.transaction_date) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div v-if="auth.user.role === 'warehouse_manager'" class="flex items-center justify-center space-x-3">
                                            <button 
                                                @click="openUpdateModal(transaction)" 
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium"
                                            >
                                                Edit
                                            </button>
                                            <button 
                                                @click="openDeleteModal(transaction)" 
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium"
                                            >
                                                Hapus
                                            </button>
                                        </div>
                                        <span v-else class="text-gray-400 dark:text-gray-500 text-sm">
                                            Tidak ada akses
                                        </span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <AdvancedPagination :pagination="transactions" />
                </div>
            </div>
        </div>

        <!-- Modal Components -->
        <CreateStockTransactionModal 
            :show="showCreateModal" 
            @close="closeModal"
            :products="props.products"  
            :users="props.users"
        />

        <UpdateStockTransactionModal 
            :show="showUpdateModal"
            :transaction="selectedTransaction" 
            @close="closeModal" 
            :products="props.products"
            :users="props.users"
        />

        <DeleteStockTransactionModal 
            :show="showDeleteModal" 
            :transaction="selectedTransaction"
            @close="closeModal" 
        />

    </AuthenticatedLayout>
</template>
