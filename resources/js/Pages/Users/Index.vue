<script setup>
import { ref, watch, onMounted } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

// Layout & Komponen UI
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import TextInput from '@/Components/TextInput.vue';

// Komponen Modal
import CreateUserModal from '@/Components/Users/CreateUserModal.vue';
import UpdateUserModal from '@/Components/Users/UpdateUserModal.vue';
import DeleteUserModal from '@/Components/Users/DeleteUserModal.vue';
import ActivityUserModal from '@/Components/Users/ActivityUserModal.vue';

// --- PROPS ---
const props = defineProps({
    users: {
        type: Object,
        default: () => ({ data: [], links: [] }),
    },
    activities: {
        type: Object,
        default: () => null,
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
        default: () => ({ search: '' }),
    },
});

// --- STATE MANAGEMENT ---
// PERBAIKAN: Pastikan 'search' selalu diinisialisasi sebagai string
const search = ref(props.filters.search || '');

// State untuk semua modal
const showCreateModal = ref(false);
const showUpdateModal = ref(false);
const showDeleteModal = ref(false);
const showActivityModal = ref(false);
const selectedUser = ref(null);
const userActivities = ref(null);

// State untuk notifikasi
const showFlash = ref({
    success: true,
    error: true,
});

// --- METHODS / FUNCTIONS ---
const openCreateModal = () => showCreateModal.value = true;

const openUpdateModal = (user) => {
    selectedUser.value = user;
    showUpdateModal.value = true;
};

const openDeleteModal = (user) => {
    selectedUser.value = user;
    showDeleteModal.value = true;
};

const openActivityModal = (user) => {
    selectedUser.value = user;
    // Menggunakan Inertia untuk mengambil data aktivitas untuk pengguna yang dipilih.
    // Opsi 'only' memastikan hanya prop 'activities' yang diperbarui.
    router.get(route('users.activity', user.id), {}, {
        preserveState: true,
        preserveScroll: true,
        only: ['activities'],
        onSuccess: (page) => {
            userActivities.value = page.props.activities;
            showActivityModal.value = true;
        },
        onError: (errors) => {
            console.error('Gagal memuat data aktivitas:', errors);
            alert('Gagal memuat data aktivitas. Periksa konsol untuk detail.');
        }
    });
};

const closeModal = () => {
    showCreateModal.value = false;
    showUpdateModal.value = false;
    showDeleteModal.value = false;
    showActivityModal.value = false;
    selectedUser.value = null;
    userActivities.value = null;
};

// --- WATCHERS & LIFECYCLE ---
watch(search, debounce((value) => {
    router.get(route('users.index'), { search: value }, {
        preserveState: true,
        replace: true,
    });
}, 300));

onMounted(() => {
    if (props.flash.success) setTimeout(() => { showFlash.value.success = false; }, 3000);
    if (props.flash.error) setTimeout(() => { showFlash.value.error = false; }, 3000);
});
</script>

<template>
    <Head title="Daftar Pengguna" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Daftar Pengguna</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola semua pengguna dalam sistem.</p>
                </div>
                <PrimaryButton @click="openCreateModal">
                    Tambah Pengguna
                </PrimaryButton>
            </div>
        </template>

        <div class="py-8">
            <div class="container mx-auto px-6">
                <!-- Notifikasi Sukses -->
                <div v-if="flash.success && showFlash.success" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</p>
                    </div>
                </div>

                <!-- Notifikasi Error -->
                <div v-if="flash.error && showFlash.error" class="mb-6 rounded-lg bg-red-50 border border-red-200 p-4 dark:bg-red-900/20 dark:border-red-800">
                     <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-sm text-red-800 dark:text-red-200">{{ flash.error }}</p>
                    </div>
                </div>

                <!-- Fitur Pencarian -->
                <div class="mb-6">
                    <div class="relative w-full max-w-md">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <TextInput v-model="search" type="text" class="block w-full h-11 pl-10" placeholder="Cari pengguna berdasarkan nama atau email..." />
                    </div>
                </div>

                <!-- Tabel Pengguna -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                <tr v-if="users.data.length === 0">
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-400 mb-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.96a3 3 0 00-4.682 2.72 9.094 9.094 0 003.741.479m7.5-2.96V11.25m-7.5 2.96V11.25m0 0a3 3 0 015.998 0m0 0a3 3 0 015.998 0M12 6v5.25a3 3 0 00-3 3v.75a3 3 0 006 0v-.75a3 3 0 00-3-3z" /></svg>
                                            <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada pengguna ditemukan</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr v-for="user in users.data" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ user.name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ user.email }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ user.role_label }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex items-center justify-center space-x-4">
                                            <button @click="openActivityModal(user)" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 font-medium">Aktivitas</button>
                                            <button @click="openUpdateModal(user)" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium">Edit</button>
                                            <button @click="openDeleteModal(user)" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 font-medium">Hapus</button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <AdvancedPagination :pagination="users" class="mt-6" />
            </div>
        </div>

        <!-- MODAL COMPONENTS -->
        <CreateUserModal :show="showCreateModal" @close="closeModal" />
        <UpdateUserModal :show="showUpdateModal" :user="selectedUser" @close="closeModal" />
        <DeleteUserModal :show="showDeleteModal" :user="selectedUser" @close="closeModal" />
        <ActivityUserModal :show="showActivityModal" :user="selectedUser" :activities="userActivities" @close="closeModal" />

    </AuthenticatedLayout>
</template>
