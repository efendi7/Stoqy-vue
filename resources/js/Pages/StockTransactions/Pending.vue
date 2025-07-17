<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';
import Textarea from '@/Components/Textarea.vue'; // Asumsi Anda punya komponen Textarea
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import { ref, watch, onMounted } from 'vue';
import { debounce } from 'lodash';

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
        default: () => ({ search: '' }),
    },
});

// --- STATE MANAGEMENT ---
const search = ref(props.filters.search);
const notes = ref({}); // Objek untuk menyimpan catatan per transaksi
const highlightedTransactionId = ref(null);

// Inisialisasi catatan dari data prop
props.transactions.data.forEach(t => {
    notes.value[t.id] = t.notes || '';
});

// --- METHODS / FUNCTIONS ---
// Watcher untuk fitur pencarian
watch(search, debounce((value) => {
    router.get(route('stock_transactions.pending'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

// Navigasi kembali ke halaman utama
const goBackToIndex = () => {
    router.get(route('stock_transactions.index'));
};

// Fungsi untuk submit konfirmasi
const submitConfirmation = (transaction) => {
    router.patch(route('stock_transactions.confirm', transaction.id), {
        note: notes.value[transaction.id] || '',
    }, {
        preserveScroll: true,
        onSuccess: () => {
            // Hapus catatan setelah sukses jika diperlukan
            delete notes.value[transaction.id];
        }
    });
};

// Highlight logic on component mount
onMounted(() => {
    const productId = usePage().props.query?.product_id;
    if (productId) {
        const transaction = props.transactions.data.find(t => t.product_id == productId);
        if (transaction) {
            highlightedTransactionId.value = transaction.id;
            const element = document.getElementById(`transaction-${transaction.id}`);
            if(element) {
                element.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
            setTimeout(() => {
                highlightedTransactionId.value = null;
            }, 2500); // Hilangkan highlight setelah 2.5 detik
        }
    }
});
</script>

<template>
    <Head title="Transaksi Pending" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">📌 Transaksi Pending</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Daftar transaksi yang memerlukan konfirmasi Anda.</p>
                </div>
                 <button @click="goBackToIndex" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                    &larr; Kembali ke Daftar Utama
                </button>
            </div>
        </template>

        <div class="py-8">
            <div class="container mx-auto px-6">
                
                <div v-if="flash.success" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</p>
                </div>
                <div v-if="flash.error" class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                    <p class="text-sm text-red-800 dark:text-red-200">{{ flash.error }}</p>
                </div>

                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <TextInput v-model="search" type="text" class="block w-full h-11 pl-10" placeholder="Cari berdasarkan nama produk..."/>
                    </div>
                </div>

                <div v-if="transactions.data.length > 0" class="space-y-4">
                    <div 
                        v-for="transaction in transactions.data" 
                        :key="transaction.id"
                        :id="`transaction-${transaction.id}`"
                        class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-5 border border-gray-200 dark:border-gray-700 transition-all duration-500 ease-in-out"
                        :class="{ 'bg-yellow-100 dark:bg-yellow-900/30': highlightedTransactionId === transaction.id }"
                    >
                        <form @submit.prevent="submitConfirmation(transaction)" class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-white">{{ transaction.product?.name || 'Produk Dihapus' }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Kuantitas: <span class="font-medium text-gray-700 dark:text-gray-200">{{ transaction.quantity }}</span></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Status: 
                                    <span class="px-3 py-1 rounded-md text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400">
                                        {{ transaction.status }}
                                    </span>
                                </p>
                            </div>

                            <div class="w-full md:w-auto flex flex-col md:flex-row-reverse items-stretch md:items-center gap-3">
                                <PrimaryButton 
                                    type="submit"
                                    :class="{
                                        'bg-blue-600 hover:bg-blue-700': transaction.type === 'Masuk',
                                        'bg-orange-600 hover:bg-orange-700': transaction.type === 'Keluar'
                                    }"
                                >
                                    {{ transaction.type === 'Masuk' ? 'Konfirmasi Masuk' : 'Konfirmasi Keluar' }}
                                </PrimaryButton>
                                <Textarea 
                                    v-model="notes[transaction.id]"
                                    rows="2"
                                    class="w-full md:w-64 text-sm"
                                    placeholder="Opsional: Tambah catatan (cth: barang rusak)"
                                />
                            </div>
                        </form>
                    </div>
                </div>

                <div v-else class="text-center py-12 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v6a2 2 0 002 2h6a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada transaksi yang menunggu konfirmasi. Kerja bagus!</p>
                </div>
                
                <div class="mt-6">
                    <AdvancedPagination :pagination="transactions" />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>