<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import WarehouseManagerDashboard from './WarehouseManagerDashboard.vue';
import WarehouseStaffDashboard from './WarehouseStaffDashboard.vue';

const props = defineProps({
    auth: Object,
    // Props untuk Manager
    metrics: {
        type: Object,
        default: () => ({}),
    },
    pendingIncoming: {
        type: Array,
        default: () => [],
    },
    pendingOutgoing: {
        type: Array,
        default: () => [],
    },
    // Props untuk Staff
    incomingTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 }),
    },
    outgoingTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 }),
    },
    completedTasks: {
        type: Object,
        default: () => ({ data: [], total: 0 }),
    },
});

const isManager = props.auth.user.role === 'warehouse_manager';
const isStaff = props.auth.user.role === 'warehouse_staff';

// Provide safe defaults for staff props
const safeIncomingTasks = props.incomingTasks || { data: [], total: 0 };
const safeOutgoingTasks = props.outgoingTasks || { data: [], total: 0 };
const safeCompletedTasks = props.completedTasks || { data: [], total: 0 };

// Provide safe defaults for manager props
const safeMetrics = props.metrics || {};
const safePendingIncoming = props.pendingIncoming || [];
const safePendingOutgoing = props.pendingOutgoing || [];
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Tampilkan dashboard yang sesuai berdasarkan role -->
                <WarehouseManagerDashboard
                    v-if="isManager"
                    :auth="auth"
                    :metrics="safeMetrics"
                    :pending-incoming="safePendingIncoming"
                    :pending-outgoing="safePendingOutgoing"
                />

                <WarehouseStaffDashboard
                    v-else-if="isStaff"
                    :auth="auth"
                    :incoming-tasks="safeIncomingTasks"
                    :outgoing-tasks="safeOutgoingTasks"
                    :completed-tasks="safeCompletedTasks"
                />
                
                <!-- Fallback untuk role lain -->
                <div v-else class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">Selamat datang! Dashboard untuk role Anda sedang dalam pengembangan.</div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>