<template>
  <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Informasi Periode -->
      <div>
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Informasi Periode</h3>
        <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ formattedPeriod }}</p>
        <div class="flex flex-col sm:flex-row gap-x-6 gap-y-2">
          <div class="flex items-center space-x-2">
            <div class="w-2.5 h-2.5 bg-green-500 rounded-full"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Masuk:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ incomingTransactions }}</span>
          </div>
          <div class="flex items-center space-x-2">
            <div class="w-2.5 h-2.5 bg-red-500 rounded-full"></div>
            <span class="text-sm text-gray-600 dark:text-gray-400">Keluar:</span>
            <span class="font-semibold text-gray-800 dark:text-gray-200">{{ outgoingTransactions }}</span>
          </div>
        </div>
      </div>

      <!-- Filter Periode -->
      <div class="border-t border-gray-200 dark:border-gray-700 pt-4 md:border-0 md:pt-0">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Filter Periode</h3>
        <div class="flex flex-col sm:flex-row sm:items-end gap-3">
          <!-- Date Inputs Container -->
          <div class="flex flex-col sm:flex-row gap-3" style="flex: 1 1 0%; min-width: 0;">
            <!-- Start Date -->
            <div class="flex-1 min-w-0">
              <label for="start_date" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Dari:</label>
              <input 
                type="date" 
                id="start_date" 
                v-model="localStartDate" 
                class="date-input w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pr-10"
              >
            </div>

            <!-- End Date -->
            <div class="flex-1 min-w-0">
              <label for="end_date" class="block mb-1 text-sm font-medium text-gray-700 dark:text-gray-300">Sampai:</label>
              <input 
                type="date" 
                id="end_date" 
                v-model="localEndDate" 
                class="date-input w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm pr-10"
              >
            </div>
          </div>

          <!-- Apply Button -->
          <div class="w-full sm:w-auto" style="flex: 0 0 auto;">
            <button 
              type="button" 
              @click="$emit('apply-filter')"
              class="h-[42px] w-full sm:w-auto px-6 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition whitespace-nowrap"
            >
              Terapkan
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script setup>
import { computed } from 'vue';
import { format, parseISO } from 'date-fns';
import { id } from 'date-fns/locale';

const props = defineProps({
  startDate: String,
  endDate: String,
  incomingTransactions: Number,
  outgoingTransactions: Number,
});

const emit = defineEmits(['update:startDate', 'update:endDate', 'apply-filter']);

const localStartDate = computed({
  get: () => props.startDate,
  set: (value) => emit('update:startDate', value)
});

const localEndDate = computed({
  get: () => props.endDate,
  set: (value) => emit('update:endDate', value)
});

const formattedPeriod = computed(() => {
  try {
    if (!props.startDate || !props.endDate) return 'Pilih periode';
    const start = format(parseISO(props.startDate), 'dd MMMM yyyy', { locale: id });
    const end = format(parseISO(props.endDate), 'dd MMMM yyyy', { locale: id });
    return `${start} - ${end}`;
  } catch {
    return 'Periode tidak valid';
  }
});
</script>

<style scoped>
/* FIX: CSS untuk memposisikan icon datepicker di ujung kanan */
.date-input::-webkit-calendar-picker-indicator {
  cursor: pointer;
  opacity: 0.6;
  transition: opacity 0.2s ease;
  margin-left: auto;
  width: 16px;
  height: 16px;
}

.date-input::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}

/* FIX: Menggunakan 'invert(1)' untuk membuat ikon menjadi putih di dark mode */
.dark .date-input::-webkit-calendar-picker-indicator {
  filter: invert(1);
}

/* Menyesuaikan padding kanan untuk memberi ruang icon */
.date-input {
  padding-right: 12px !important;
}
</style>