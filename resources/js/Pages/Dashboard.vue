<template>
  <div>
    <div class="container mx-auto px-4 mt-8 relative z-10">
      
      <WelcomeBanner :user-name="props.userName" :user-role-label="props.userRoleLabel" />

      <!-- 
        FIX 1: Event listener @apply-filter="submitFilters" ditambahkan.
        Ini adalah bagian paling krusial yang menghubungkan tombol "Terapkan"
        dengan fungsi yang mengirim data ke server.
      -->
      <PeriodFilter
          @apply-filter="submitFilters"
          v-model:startDate="filterForm.start_date" 
          v-model:endDate="filterForm.end_date"
          :incoming-transactions="props.incomingTransactions"
          :outgoing-transactions="props.outgoingTransactions"
      />

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
        <MetricCard
          v-for="metric in props.metrics"
          :key="metric.label"
          :label="metric.label"
          :value="metric.value"
          :icon="metric.icon"
          :delay="metric.delay"
        />
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <ChartCard title="Stok Barang" icon="📊" :has-data="props.stockData && props.stockData.length > 0">
          <canvas ref="stockChartCanvas"></canvas>
        </ChartCard>

        <ChartCard title="Laporan Transaksi" icon="📈" :has-data="props.transactionLabels && props.transactionLabels.length > 0">
          <canvas ref="transactionChartCanvas"></canvas>
        </ChartCard>
      </div>
      
      <ActivityLog />

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import { useForm } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import WelcomeBanner from '@/Components/Dashboard/WelcomeBanner.vue';
import PeriodFilter from '@/Components/Dashboard/PeriodFilter.vue';
import MetricCard from '@/Components/Dashboard/MetricCard.vue';
import ChartCard from '@/Components/Dashboard/ChartCard.vue';
import ActivityLog from '@/Components/Dashboard/ActivityLog.vue';

defineOptions({
  layout: AuthenticatedLayout,
});

const props = defineProps({
  userRoleLabel: String,
  userName: String,
  metrics: Array,
  stockLabels: Array,
  stockData: Array,
  transactionLabels: Array,
  incomingTransactionData: Array,
  outgoingTransactionData: Array,
  startDate: String,
  endDate: String,
  incomingTransactions: Number,
  outgoingTransactions: Number,
});

const stockChartCanvas = ref(null);
const transactionChartCanvas = ref(null);

let stockChartInstance = null;
let transactionChartInstance = null;

const filterForm = useForm({
    start_date: props.startDate,
    end_date: props.endDate,
});

// FIX 2: Fungsi ini sekarang akan dipanggil oleh event dari anak.
function submitFilters() {
    // Untuk debugging, pastikan fungsi ini terpanggil
    console.log('Submitting filters:', filterForm.data());

    filterForm.get(route('dashboard'), {
        preserveState: true,
        preserveScroll: true,
    });
}

// ... fungsi chart lainnya ...
function initializeStockChart() {
  if (stockChartInstance) stockChartInstance.destroy();
  if (stockChartCanvas.value && props.stockLabels?.length) {
    const ctx = stockChartCanvas.value.getContext('2d');
    stockChartInstance = new Chart(ctx, { /* ... config ... */ });
  }
}

function initializeTransactionChart() {
  if (transactionChartInstance) transactionChartInstance.destroy();
  if (transactionChartCanvas.value && props.transactionLabels?.length) {
    const ctx = transactionChartCanvas.value.getContext('2d');
    transactionChartInstance = new Chart(ctx, { /* ... config ... */ });
  }
}

onMounted(() => {
  initializeStockChart();
  initializeTransactionChart();
});

// Watcher untuk update chart setelah data baru diterima
watch(() => [props.stockData, props.transactionLabels], () => {
    initializeStockChart();
    initializeTransactionChart();
}, { deep: true });
</script>
