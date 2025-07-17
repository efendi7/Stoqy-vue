<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AnimateBackground from '@/Components/Dashboard/AnimateBackground.vue';
import WelcomeBanner from '@/Components/Dashboard/WelcomeBanner.vue';
import MetricCard from '@/Components/Dashboard/MetricCard.vue';
// Hapus import TransactionListCard karena tidak digunakan lagi
// import TransactionListCard from '@/Components/Dashboard/TransactionListCard.vue';

defineOptions({
    layout: AuthenticatedLayout,
});

const props = defineProps({
    auth: Object,
    metrics: Array,
    pendingIncoming: Array,
    pendingOutgoing: Array,
});

// Fungsi ini tetap ada untuk MetricCard
const getMetricColor = (label) => {
    if (!label) return 'gray';
    if (label.includes('Rendah') || label.includes('Habis')) return 'red';
    if (label.includes('Masuk')) return 'blue';
    if (label.includes('Keluar')) return 'yellow';
    return 'green';
};

// --- TAMBAHKAN HELPER FUNCTIONS DARI DASHBOARD STAFF ---
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
</script>

<template>
    <Head title="Warehouse Dashboard" />
    
    <div class="p-4 relative min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-300">
        <AnimateBackground />
        
        <div class="container mx-auto px-4 mt-8 relative z-10">
            <WelcomeBanner
                :user-name="auth?.user?.name"
                user-role-label="Warehouse Manager Dashboard"
                icon="🏭"
            />
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                <MetricCard
                    v-for="(metric, index) in (metrics || [])"
                    :key="index"
                    :label="metric.label"
                    :value="metric.value"
                    :icon="metric.icon"
                    :color-theme="getMetricColor(metric.label)"
                    :delay="index * 100"
                />
            </div>
            
            <div class="space-y-8">
                <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-6 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/50 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📥</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    Transaksi Masuk Pending
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Daftar transaksi masuk yang memerlukan persetujuan.
                                </p>
                            </div>
                        </div>
                        <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-700 dark:text-blue-200 px-4 py-2 rounded-full font-semibold">
                            {{ pendingIncoming.length }}
                        </div>
                    </div>

                    <div v-if="pendingIncoming.length === 0" class="text-center py-12">
                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/80 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">📦</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-300">Tidak ada transaksi masuk yang tertunda.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="task in pendingIncoming" 
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
                                    :href="route('stock_transactions.index', { id: task.id })" 
                                    class="inline-flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 font-semibold hover:text-amber-800 dark:hover:text-amber-200 transition-colors"
                                >
                                    <span>🔍</span>
                                    <span>Lihat & Setujui</span>
                                </Link>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    Dibuat: {{ new Date(task.created_at).toLocaleString('id-ID') }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white/90 dark:bg-gray-900/90 backdrop-blur-sm p-6 shadow-lg rounded-2xl border border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-red-100 dark:bg-red-900/50 rounded-xl flex items-center justify-center">
                                <span class="text-2xl">📤</span>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">
                                    Transaksi Keluar Pending
                                </h2>
                                <p class="text-sm text-gray-600 dark:text-gray-300">
                                    Daftar transaksi keluar yang memerlukan persetujuan.
                                </p>
                            </div>
                        </div>
                        <div class="bg-red-100 dark:bg-red-900/50 text-red-700 dark:text-red-200 px-4 py-2 rounded-full font-semibold">
                            {{ pendingOutgoing.length }}
                        </div>
                    </div>

                    <div v-if="pendingOutgoing.length === 0" class="text-center py-12">
                         <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700/80 rounded-full flex items-center justify-center mx-auto mb-4">
                            <span class="text-2xl">🚚</span>
                        </div>
                        <p class="text-gray-500 dark:text-gray-300">Tidak ada transaksi keluar yang tertunda.</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div 
                            v-for="task in pendingOutgoing" 
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
                                    :href="route('stock_transactions.index', { id: task.id })" 
                                    class="inline-flex items-center space-x-2 text-sm text-amber-600 dark:text-amber-400 font-semibold hover:text-amber-800 dark:hover:text-amber-200 transition-colors"
                                >
                                    <span>🔍</span>
                                    <span>Lihat & Setujui</span>
                                </Link>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                     Dibuat: {{ new Date(task.created_at).toLocaleString('id-ID') }}
                                </p>
                            </div>
                        </div>
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