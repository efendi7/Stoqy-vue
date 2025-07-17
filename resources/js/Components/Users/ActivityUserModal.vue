<script setup>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

// --- PROPS ---
// Menerima data yang diperlukan dari komponen induk
defineProps({
    show: { type: Boolean, default: false },
    user: { type: Object, default: () => null },
    activities: { type: Object, default: () => ({ data: [] }) },
});

// --- EMITS ---
// Memberi tahu komponen induk untuk menutup modal
const emit = defineEmits(['close']);

// --- METHODS ---
// Fungsi untuk memformat tanggal dan waktu
const formatDateTime = (timestamp) => {
    if (!timestamp) return '';
    const date = new Date(timestamp);
    return date.toLocaleString('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        timeZone: 'Asia/Jakarta'
    }).replace(/\./g, ':');
};

// Fungsi untuk membuat huruf pertama kapital
const capitalizeFirstLetter = (string) => {
    if (!string) return '';
    return string.charAt(0).toUpperCase() + string.slice(1);
}
</script>

<template>
    <Modal :show="show" @close="$emit('close')" max-width="2xl">
        <!-- v-if="user" digunakan untuk memastikan konten tidak dirender sampai data user ada -->
        <div v-if="user" class="p-6 bg-white dark:bg-gray-800">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Aktivitas Pengguna:
                <!-- PERBAIKAN: Menggunakan optional chaining (?.) untuk mengakses nama dengan aman -->
                <span class="font-bold">{{ user?.name }}</span>
            </h2>

            <!-- Konten Modal -->
            <div class="max-h-[60vh] overflow-y-auto">
                <!-- Jika tidak ada aktivitas -->
                <div v-if="!activities || activities.data.length === 0" class="text-center py-12">
                    <div class="flex flex-col items-center justify-center">
                        <svg class="w-16 h-16 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">Belum ada aktivitas yang tercatat.</p>
                    </div>
                </div>

                <!-- Tabel Aktivitas -->
                <div v-else class="border border-gray-200 dark:border-gray-700 rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aktivitas</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Waktu</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            <tr v-for="activity in activities.data" :key="activity.id">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ capitalizeFirstLetter(activity.action) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ formatDateTime(activity.created_at) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tombol Tutup -->
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="$emit('close')">
                    Tutup
                </SecondaryButton>
            </div>
        </div>
    </Modal>
</template>