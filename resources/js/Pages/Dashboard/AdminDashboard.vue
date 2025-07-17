<template>
  <div class="p-4">
    <AnimateBackground />

    <div class="container mx-auto px-4 mt-8 relative z-10">
      <WelcomeBanner :user-name="props.userName" :user-role-label="props.userRoleLabel" />

      <PeriodFilter
        :start-date="filterForm.start_date"
        :end-date="filterForm.end_date"
        :incoming-transactions="props.incomingTransactions"
        :outgoing-transactions="props.outgoingTransactions"
        v-model:startDate="filterForm.start_date" 
        v-model:endDate="filterForm.end_date"
        @apply-filter="submitFilters"
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
          <template #filters>
            <div class="flex flex-wrap items-center gap-2">
              <!-- Filter Kategori -->
              <select v-model="filterForm.stock_category" class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="all">Semua Kategori</option>
                <option v-for="category in props.allCategories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
              <!-- Filter Jumlah Tampilan (Top N) -->
              <select v-model="filterForm.stock_limit" class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="5">Top 5</option>
                <option value="10">Top 10</option>
                <option value="15">Top 15</option>
              </select>
              <!-- Filter Urutan -->
              <select v-model="filterForm.stock_sort" class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="desc">Terbanyak</option>
                <option value="asc">Tersedikit</option>
              </select>
            </div>
          </template>
          <canvas ref="stockChartCanvas"></canvas>
        </ChartCard>

        <!-- FIX: Menambahkan filter status pada Chart Transaksi -->
        <ChartCard title="Laporan Transaksi" icon="📈" :has-data="props.transactionLabels && props.transactionLabels.length > 0">
          <template #filters>
            <div class="w-full">
    <select 
      v-model="filterForm.transaction_status"
      class="text-sm border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
    >
      <option value="all">Semua Status</option>
      <option value="Pending">Pending</option>
      <option value="Diterima">Diterima</option>
      <option value="Ditolak">Ditolak</option>
    </select>
  </div>
          </template>
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
import debounce from 'lodash/debounce';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AnimateBackground from '@/Components/Dashboard/AnimateBackground.vue';
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
  allCategories: Array,
  selectedCategory: [String, Number],
  selectedStockSort: String,
  selectedStockLimit: [String, Number],
  // FIX: Tambahkan prop untuk status transaksi yang dipilih
  selectedTransactionStatus: String,
});

const stockChartCanvas = ref(null);
const transactionChartCanvas = ref(null);

let stockChartInstance = null;
let transactionChartInstance = null;

// FIX: Tambahkan state untuk filter status transaksi ke dalam useForm
const filterForm = useForm({
  start_date: props.startDate,
  end_date: props.endDate,
  stock_category: props.selectedCategory || 'all',
  stock_limit: props.selectedStockLimit || 10,
  stock_sort: props.selectedStockSort || 'desc',
  transaction_status: props.selectedTransactionStatus || 'all',
});

function submitFilters() {
  filterForm.get(route('dashboard'), {
    preserveState: true,
    preserveScroll: true,
  });
}

// FIX: Tambahkan watcher untuk filter status transaksi
watch(
  () => [filterForm.stock_category, filterForm.stock_limit, filterForm.stock_sort, filterForm.transaction_status],
  debounce(() => {
    submitFilters();
  }, 300) // Menunggu 300ms setelah user selesai memilih sebelum mengirim request
);

function initializeStockChart() {
  if (stockChartInstance) stockChartInstance.destroy();
  if (stockChartCanvas.value && props.stockLabels?.length) {
    const ctx = stockChartCanvas.value.getContext('2d');
    stockChartInstance = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: props.stockLabels,
        datasets: [{
          label: 'Jumlah Stok',
          data: props.stockData,
          backgroundColor: 'rgba(54, 162, 235, 0.6)',
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 1,
        }]
      },
      options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          x: {
            beginAtZero: true
          }
        },
        plugins: {
          legend: {
            display: false
          }
        }
      }
    });
  }
}

function initializeTransactionChart() {
  if (transactionChartInstance) transactionChartInstance.destroy();
  if (transactionChartCanvas.value && props.transactionLabels?.length) {
    const ctx = transactionChartCanvas.value.getContext('2d');
    transactionChartInstance = new Chart(ctx, {
      type: 'line',
      data: {
        labels: props.transactionLabels,
        datasets: [
          {
            label: 'Transaksi Masuk',
            data: props.incomingTransactionData,
            borderColor: 'rgba(75, 192, 192, 1)',
            tension: 0.1,
          },
          {
            label: 'Transaksi Keluar',
            data: props.outgoingTransactionData,
            borderColor: 'rgba(255, 99, 132, 1)',
            tension: 0.1,
          },
        ],
      },
      options: {
          responsive: true,
          maintainAspectRatio: false,
      }
    });
  }
}

onMounted(() => {
  initializeStockChart();
  initializeTransactionChart();
});

watch(() => [props.stockData, props.transactionLabels], () => {
  initializeStockChart();
  initializeTransactionChart();
}, { deep: true });
</script>
