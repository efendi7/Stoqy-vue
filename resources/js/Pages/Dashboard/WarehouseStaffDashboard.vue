<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AdvancedPagination from '@/Components/AdvancedPagination.vue';
import AnimateBackground from '@/Components/Dashboard/AnimateBackground.vue';
import WelcomeBanner from '@/Components/Dashboard/WelcomeBanner.vue';
import MetricCard from '@/Components/Dashboard/MetricCard.vue';

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
    auth: Object,
    incomingTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 })
    },
    outgoingTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 })
    },
    completedTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 })
    },
});

const getStatusClass = (status) => {
    if (status === 'Diterima') return 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-200';
    if (status === 'Ditolak') return 'bg-red-100 text-red-700 dark:bg-red-900/50 dark:text-red-200';
    return 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-200';
};

const getTypeClass = (type) => {
    if (type === 'incoming') return 'bg-blue-50 dark:bg-blue-900/30 border-blue-200 dark:border-blue-600';
    if (type === 'outgoing') return 'bg-red-50 dark:bg-red-900/30 border-red-200 dark:border-red-600';
    return 'bg-green-50 dark:bg-green-900/30 border-green-200 dark:border-green-600';
};

const getTypeTextClass = (type) => {
    if (type === 'incoming') return 'text-blue-700 dark:text-blue-200';
    if (type === 'outgoing') return 'text-red-700 dark:text-red-200';
    return 'text-green-700 dark:text-green-200';
};

const getTypeBadgeClass = (type) => {
    if (type === 'incoming') return 'bg-blue-600 dark:bg-blue-500';
    if (type === 'outgoing') return 'bg-red-600 dark:bg-red-500';
    return 'bg-green-600 dark:bg-green-500';
};

// Safe accessors with fallback values
const safeIncomingTasks = props.incomingTasks || { data: [], total: 0 };
const safeOutgoingTasks = props.outgoingTasks || { data: [], total: 0 };
const safeCompletedTasks = props.completedTasks || { data: [], total: 0 };

// Calculate metrics
const metrics = [
    {
        label: 'Tugas Masuk',
        value: safeIncomingTasks.total,
        icon: '📘',
        delay: 0
    },
    {
        label: 'Tugas Keluar',
        value: safeOutgoingTasks.total,
        icon: '📕',
        delay: 100
    },
    {
        label: 'Tugas Selesai',
        value: safeCompletedTasks.total,
        icon: '✅',
        delay: 200
    },
    {
        label: 'Total Tugas',
        value: safeIncomingTasks.total + safeOutgoingTasks.total + safeCompletedTasks.total,
        icon: '📊',
        delay: 300
    }
];
</script>

<template>
    <div class="p-4">
        <AnimateBackground />

        <div class="container mx-auto px-4 mt-8 relative z-10">
            <!-- Welcome Banner -->
            <WelcomeBanner 
                :user-name="auth.user.name" 
                user-role-label="Warehouse Staff" 
            />

            <!-- Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <MetricCard
                    v-for="metric in metrics"
                    :key="metric.label"
                    :label="metric.label"
                    :value="metric.value"
                    :icon="metric.icon"
                    :delay="metric.delay"
                />
            </div>

            <!-- Task Sections -->
            <div class="space-y-8">
                <!-- Incoming Tasks -->
                <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-6 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📘</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    Tugas Baru - Barang Masuk
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Daftar barang yang perlu diperiksa masuk
                                </p>
                            </div>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-200 px-4 py-2 rounded-full font-semibold">
                            {{ safeIncomingTasks.total }}
                        </div>
                    </div>

                    <div v-if="safeIncomingTasks.data.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/80 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📭</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-300">Tidak ada tugas barang masuk saat ini</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="task in safeIncomingTasks.data" 
                            :key="task.id"
                            :class="getTypeClass('incoming')"
                            class="p-4 rounded-xl border-2 transition-all duration-300 hover:shadow-lg hover:scale-105"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <h3 :class="getTypeTextClass('incoming')" class="font-semibold text-lg truncate pr-2">
                                    {{ task.product?.name || 'N/A' }}
                                </h3>
                                <span :class="getTypeBadgeClass('incoming')" class="text-white font-bold px-3 py-1 rounded-full text-sm">
                                    {{ task.quantity }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <Link 
                                    :href="route('stock_transactions.pending', { product_id: task.product.id })" 
                                    class="inline-flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 font-semibold hover:text-amber-800 dark:hover:text-amber-200 transition-colors"
                                >
                                    <span>⚠️</span>
                                    <span>Perlu diperiksa</span>
                                </Link>
                                
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(task.created_at).toLocaleString('id-ID') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="safeIncomingTasks.data.length > 0" class="mt-6">
                        <AdvancedPagination :pagination="safeIncomingTasks" />
                    </div>
                </div>

                <!-- Outgoing Tasks -->
                <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-6 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📕</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    Tugas Baru - Barang Keluar
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Daftar barang yang perlu diperiksa keluar
                                </p>
                            </div>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200 px-4 py-2 rounded-full font-semibold">
                            {{ safeOutgoingTasks.total }}
                        </div>
                    </div>

                    <div v-if="safeOutgoingTasks.data.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/80 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📤</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-300">Tidak ada tugas barang keluar saat ini</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="task in safeOutgoingTasks.data" 
                            :key="task.id"
                            :class="getTypeClass('outgoing')"
                            class="p-4 rounded-xl border-2 transition-all duration-300 hover:shadow-lg hover:scale-105"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <h3 :class="getTypeTextClass('outgoing')" class="font-semibold text-lg truncate pr-2">
                                    {{ task.product?.name || 'N/A' }}
                                </h3>
                                <span :class="getTypeBadgeClass('outgoing')" class="text-white font-bold px-3 py-1 rounded-full text-sm">
                                    {{ task.quantity }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <Link 
                                    :href="route('stock_transactions.pending', { product_id: task.product.id })" 
                                    class="inline-flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 font-semibold hover:text-amber-800 dark:hover:text-amber-200 transition-colors"
                                >
                                    <span>⚠️</span>
                                    <span>Perlu diperiksa</span>
                                </Link>
                                
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(task.created_at).toLocaleString('id-ID') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="safeOutgoingTasks.data.length > 0" class="mt-6">
                        <AdvancedPagination :pagination="safeOutgoingTasks" />
                    </div>
                </div>

                <!-- Completed Tasks -->
                <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-6 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 dark:bg-green-900/50 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">✅</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    Tugas yang Telah Diproses
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Riwayat tugas yang sudah selesai diproses
                                </p>
                            </div>
                        </div>
                        <div class="bg-green-100 dark:bg-green-900/50 text-green-700 dark:text-green-200 px-4 py-2 rounded-full font-semibold">
                            {{ safeCompletedTasks.total }}
                        </div>
                    </div>

                    <div v-if="safeCompletedTasks.data.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/80 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📋</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-300">Tidak ada tugas yang telah diproses</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="task in safeCompletedTasks.data" 
                            :key="task.id"
                            :class="getTypeClass('completed')"
                            class="p-4 rounded-xl border-2 transition-all duration-300 hover:shadow-lg hover:scale-105"
                        >
                            <div class="flex justify-between items-start mb-3">
                                <h3 :class="getTypeTextClass('completed')" class="font-semibold text-lg truncate pr-2">
                                    {{ task.product?.name || 'N/A' }}
                                </h3>
                                <span :class="getTypeBadgeClass('completed')" class="text-white font-bold px-3 py-1 rounded-full text-sm">
                                    {{ task.quantity }}
                                </span>
                            </div>
                            
                            <div class="space-y-2">
                                <span class="inline-block text-xs font-semibold px-3 py-1 rounded-full" :class="getStatusClass(task.status)">
                                    {{ task.status === 'Confirmed' ? 'Menunggu Manajer' : task.status }}
                                </span>
                                
                                <Link 
                                    :href="route('stock_transactions.confirmed', { product_id: task.product.id })" 
                                    class="inline-flex items-center space-x-2 text-sm text-blue-600 dark:text-blue-400 font-semibold hover:text-blue-800 dark:hover:text-blue-200 transition-colors"
                                >
                                    <span>🔍</span>
                                    <span>Periksa Detail</span>
                                </Link>
                                
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(task.created_at).toLocaleString('id-ID') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div v-if="safeCompletedTasks.data.length > 0" class="mt-6">
                        <AdvancedPagination :pagination="safeCompletedTasks" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Additional animations for smooth transitions */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in-up {
    animation: fadeInUp 0.6s ease-out forwards;
}

/* Glassmorphism effect */
.backdrop-blur-sm {
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

/* Hover effects */
.hover\:scale-105:hover {
    transform: scale(1.05);
}

.transition-all {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
</style>