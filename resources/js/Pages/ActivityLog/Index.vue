<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// --- PROPS ---
const props = defineProps({
    aktivitas: {
        type: Object,
        default: () => ({ data: [] }),
    },
    flash: {
        type: Object,
        default: () => ({}),
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
    filters: {
        type: Object,
        default: () => ({}),
    }
});

// --- STATE MANAGEMENT ---

// State untuk menampilkan/menyembunyikan pesan flash success
const showSuccessFlash = ref(true);

// Menggunakan useForm dari Inertia untuk mengelola form filter
// Inisialisasi nilai form dari props filter yang dikirim dari controller
const filterForm = useForm({
    tanggal_mulai: props.filters.tanggal_mulai || '',
    tanggal_akhir: props.filters.tanggal_akhir || '',
});

// --- METHODS / FUNCTIONS ---

// Fungsi untuk menerapkan filter tanggal
const applyFilter = () => {
    filterForm.get(route('laporan.aktivitas.index'), {
        preserveState: true, // Pertahankan state komponen (misal: posisi scroll)
        replace: true,       // Ganti history state, jangan menambah baru
    });
};

// Fungsi untuk mereset filter ke rentang default (misal: 1 bulan terakhir)
const resetToDefault = () => {
    const today = new Date().toISOString().split('T')[0];
    const oneMonthAgo = new Date();
    oneMonthAgo.setMonth(oneMonthAgo.getMonth() - 1);
    const lastMonth = oneMonthAgo.toISOString().split('T')[0];

    filterForm.tanggal_mulai = lastMonth;
    filterForm.tanggal_akhir = today;
    applyFilter();
};

// Fungsi untuk konfirmasi dan menghapus semua log
const confirmDeleteAll = () => {
    if (confirm('Apakah Anda yakin ingin menghapus semua log aktivitas?')) {
        router.delete(route('laporan.aktivitas.delete-all'), {
            preserveScroll: true, // Pertahankan posisi scroll setelah aksi
        });
    }
};

// Fungsi untuk konfirmasi dan menghapus log tunggal
const confirmDeleteLog = (logId) => {
    if (confirm('Yakin ingin menghapus log ini?')) {
        router.delete(route('laporan.aktivitas.hapus', logId), {
            preserveScroll: true,
        });
    }
};

// Fungsi untuk memformat tanggal agar mudah dibaca
const formatDateTime = (timestamp) => {
    const date = new Date(timestamp);
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Jakarta'
    }).replace(/\./g, ':');
};


// --- LIFECYCLE HOOKS ---

// Sembunyikan flash message setelah 3 detik
onMounted(() => {
    if (props.flash.success) {
        setTimeout(() => {
            showSuccessFlash.value = false;
        }, 3000);
    }
});

</script>

<template>
    <Head title="Laporan Aktivitas Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <h1 class="text-3xl font-extrabold text-slate-700 dark:text-slate-200">
                Laporan Aktivitas Pengguna
            </h1>
        </template>

        <div class="container mx-auto px-4 py-6">

            <div v-if="Object.keys(errors).length > 0" class="bg-red-600 border border-red-400 text-white px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Ada kesalahan!</strong>
                <ul class="mt-2 list-disc list-inside">
                    <li v-for="(error, key) in errors" :key="key">{{ error }}</li>
                </ul>
            </div>

            <transition
                enter-active-class="transition ease-out duration-300"
                enter-from-class="opacity-0 transform -translate-y-2"
                enter-to-class="opacity-100 transform translate-y-0"
                leave-active-class="transition ease-in duration-300"
                leave-from-class="opacity-100 transform translate-y-0"
                leave-to-class="opacity-0 transform -translate-y-2"
            >
                <div v-if="flash.success && showSuccessFlash" class="max-w-lg mx-auto bg-green-500 text-white p-3 rounded-lg mb-6 flex justify-between items-center shadow-lg backdrop-blur-md">
                    <div class="flex items-center space-x-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <span>{{ flash.success }}</span>
                    </div>
                    <button @click="showSuccessFlash = false" class="text-white font-bold hover:text-gray-200">✖</button>
                </div>
            </transition>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md mb-8">
                <h3 class="text-gray-600 dark:text-gray-300 text-sm font-medium mb-4">Filter Periode</h3>
                <form @submit.prevent="applyFilter" class="flex flex-wrap items-center gap-4">
                    <div class="flex items-center">
                        <label for="tanggal_mulai" class="mr-2 text-sm dark:text-gray-400">Dari</label>
                        <input type="date" id="tanggal_mulai" v-model="filterForm.tanggal_mulai" class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    </div>
                    <div class="flex items-center">
                        <label for="tanggal_akhir" class="mr-2 text-sm dark:text-gray-400">Sampai</label>
                        <input type="date" id="tanggal_akhir" v-model="filterForm.tanggal_akhir" class="border rounded px-2 py-1 text-sm dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600">
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-600 transition" :disabled="filterForm.processing">
                            {{ filterForm.processing ? 'Memuat...' : 'Terapkan' }}
                        </button>
                        <button type="button" @click="resetToDefault" class="bg-gray-500 text-white px-4 py-1 rounded hover:bg-gray-600 transition">Reset</button>
                    </div>
                </form>
            </div>

            <div class="mb-4 flex justify-end">
                <button @click="confirmDeleteAll" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 shadow-md transition">
                    Hapus Semua Log
                </button>
            </div>

            <div class="overflow-x-auto rounded-lg shadow-lg bg-white bg-opacity-70 dark:bg-gray-800 dark:bg-opacity-80">
                <table class="min-w-full border-collapse border border-gray-300 dark:border-gray-700">
                    <thead class="bg-gray-800 bg-opacity-80 text-white">
                        <tr>
                            <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Nama Pengguna</th>
                            <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Role</th>
                            <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-left">Aksi</th>
                            <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-center">Waktu</th>
                            <th class="py-3 px-4 border-b border-gray-300 dark:border-gray-700 text-center">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        <tr v-if="aktivitas.data.length === 0">
                            <td colspan="5" class="py-6 px-4 text-center text-gray-500 dark:text-gray-400">
                                Tidak ada data aktivitas untuk ditampilkan.
                            </td>
                        </tr>
                        <tr v-for="log in aktivitas.data" :key="log.id" class="hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <td class="py-3 px-4 whitespace-nowrap dark:text-gray-300">{{ log.user?.name || 'Tidak Diketahui' }}</td>
                            <td class="py-3 px-4 whitespace-nowrap dark:text-gray-400">{{ log.user?.role || 'Tanpa Role' }}</td>
                            <td class="py-3 px-4 dark:text-gray-300">{{ log.action }}</td>
                            <td class="py-3 px-4 text-center whitespace-nowrap dark:text-gray-400">{{ formatDateTime(log.created_at) }}</td>
                            <td class="py-3 px-4 text-center">
                                <button @click="confirmDeleteLog(log.id)" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 transition text-sm">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="aktivitas.data.length > 0" class="mt-6 flex justify-center">
                <nav>
                    <ul class="flex items-center -space-x-px h-8 text-sm">
                        <li v-for="(link, index) in aktivitas.links" :key="index">
                            <a
                                @click.prevent="router.get(link.url)"
                                href="#"
                                :class="[
                                    'flex items-center justify-center px-3 h-8 leading-tight',
                                    { 'bg-blue-50 text-blue-600 dark:bg-gray-700 dark:text-white': link.active },
                                    { 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 hover:text-gray-700 dark:bg-gray-800 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white': !link.active }
                                ]"
                                v-html="link.label"
                            >
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </AuthenticatedLayout>
</template>