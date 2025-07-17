<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';

// --- PROPS ---
// Mendefinisikan props yang diterima dari controller Laravel via Inertia
const props = defineProps({
    stok: {
        type: Object,
        default: () => ({ data: [], links: [] }), // Data stok yang dipaginasi
    },
    categories: {
        type: Array,
        default: () => [], // Daftar semua kategori untuk filter
    },
    errors: {
        type: Object,
        default: () => ({}), // Error validasi
    },
    filters: {
        type: Object,
        default: () => ({}), // Nilai filter yang sedang aktif
    },
});

// --- STATE MANAGEMENT ---
// Menggunakan useForm untuk mengelola state form filter.
// Nilai awal diambil dari props.filters agar form tetap terisi setelah filter diterapkan.
const filterForm = useForm({
    category: props.filters.category || '',
    start_date: props.filters.start_date || '',
    end_date: props.filters.end_date || '',
});

// --- METHODS / FUNCTIONS ---

// Fungsi untuk mengirim data filter ke backend
const applyFilter = () => {
    // Menggunakan metode GET untuk mengirim form filter ke route yang sesuai
    filterForm.get(route('laporan.stok.filter'), {
        preserveState: true, // Pertahankan state lokal komponen
        replace: true,       // Ganti URL di history, jangan membuat entri baru
    });
};

</script>

<template>
    <Head title="Laporan Stok Barang" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-3xl font-extrabold text-slate-700 dark:text-slate-200">
                Laporan Stok Barang
            </h1>
        </template>

        <div class="container mx-auto px-4 py-6">

            <!-- Notifikasi Kesalahan Validasi -->
            <div v-if="Object.keys(errors).length > 0" class="bg-red-600 border border-red-400 text-white px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Ada kesalahan!</strong>
                <ul class="mt-2 list-disc list-inside">
                    <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <!-- Form Filter -->
            <div class="mb-6 bg-white dark:bg-gray-800 p-4 rounded-lg shadow-md">
                <form @submit.prevent="applyFilter" class="flex flex-wrap items-center gap-4">
                    <!-- Filter Kategori -->
                    <div class="flex-grow md:flex-grow-0">
                        <select v-model="filterForm.category" class="w-full border p-2 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                            <option value="">Semua Kategori</option>
                            <option v-for="category in categories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                    </div>
                    <!-- Filter Tanggal Mulai -->
                    <div class="flex-grow md:flex-grow-0">
                        <input type="date" v-model="filterForm.start_date" class="w-full border p-2 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    </div>
                    <!-- Filter Tanggal Akhir -->
                    <div class="flex-grow md:flex-grow-0">
                        <input type="date" v-model="filterForm.end_date" class="w-full border p-2 rounded-lg dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    </div>
                    <!-- Tombol Filter -->
                    <div>
                        <button type="submit" class="bg-green-500 text-white px-6 py-2 rounded-lg hover:bg-green-600 transition w-full md:w-auto" :disabled="filterForm.processing">
                            {{ filterForm.processing ? 'Memfilter...' : 'Filter' }}
                        </button>
                    </div>
                </form>
            </div>


            <!-- Tabel Stok Barang -->
            <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-50 dark:bg-gray-800 dark:bg-opacity-80">
                <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-800 bg-opacity-80 text-white">
                        <tr>
                            <th class="py-3 px-4 text-left">Produk</th>
                            <th class="py-3 px-4 text-left">Kategori</th>
                            <th class="py-3 px-4 text-center">Stok Awal</th>
                            <th class="py-3 px-4 text-center">Barang Masuk</th>
                            <th class="py-3 px-4 text-center">Barang Keluar</th>
                            <th class="py-3 px-4 text-center bg-blue-900/50">SO Masuk</th>
                            <th class="py-3 px-4 text-center bg-red-900/50">SO Keluar</th>
                            <th class="py-3 px-4 text-center font-bold">Stok Akhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="stok.data.length === 0">
                            <td colspan="8" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data stok untuk ditampilkan. Silakan sesuaikan filter Anda.
                            </td>
                        </tr>
                        <tr v-for="item in stok.data" :key="item.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="py-3 px-4 whitespace-nowrap dark:text-gray-300">{{ item.name }}</td>
                            <td class="py-3 px-4 whitespace-nowrap dark:text-gray-400">{{ item.category?.name || 'N/A' }}</td>
                            <td class="py-3 px-4 text-center dark:text-gray-300">{{ item.stok_awal }}</td>
                            <td class="py-3 px-4 text-center dark:text-gray-300">{{ item.barang_masuk || 0 }}</td>
                            <td class="py-3 px-4 text-center dark:text-gray-300">{{ item.barang_keluar || 0 }}</td>
                            <td class="py-3 px-4 text-center bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300">{{ item.stock_opname_masuk || 0 }}</td>
                            <td class="py-3 px-4 text-center bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300">{{ item.stock_opname_keluar || 0 }}</td>
                            <td class="py-3 px-4 text-center font-semibold dark:text-white">{{ item.stok_akhir }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="stok.data.length > 0 && stok.links.length > 3" class="mt-6 flex justify-center">
                <nav>
                    <ul class="flex items-center -space-x-px h-8 text-sm">
                        <li v-for="(link, index) in stok.links" :key="index">
                            <!-- Non-clickable link untuk '...' -->
                            <span v-if="!link.url"
                                  class="flex items-center justify-center px-3 h-8 leading-tight text-gray-500 bg-white border border-gray-300 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400"
                                  v-html="link.label">
                            </span>
                             <!-- Clickable link untuk halaman lain -->
                            <a v-else
                                @click.prevent="router.get(link.url, {}, { preserveState: true })"
                                href="#"
                                :class="[
                                    'flex items-center justify-center px-3 h-8 leading-tight cursor-pointer',
                                    { 'bg-blue-50 text-blue-600 border-blue-300 dark:bg-gray-700 dark:text-white': link.active },
                                    { 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active }
                                ]"
                                v-html="link.label"
                            ></a>
                        </li>
                    </ul>
                </nav>
            </div>

        </div>
    </AuthenticatedLayout>
</template>
