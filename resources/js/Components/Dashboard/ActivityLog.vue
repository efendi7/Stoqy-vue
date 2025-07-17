<template>
  <div
    class="relative backdrop-blur-xl bg-gray-50/80 border border-gray-200/50 p-8 rounded-2xl shadow-lg animate-fade-in-up delay-500 dark:bg-white/5 dark:border-white/10 dark:shadow-2xl"
  >
    <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center dark:text-gray-100">
      <span class="text-2xl mr-3">🔥</span>Aktivitas Terbaru
      <div v-if="isLoading || activities.length > 0" class="ml-auto w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
    </h3>

    <!-- Skeleton Loading -->
    <div v-if="isLoading" class="space-y-4">
      <div v-for="n in 5" :key="n" class="animate-pulse h-6 bg-gray-200 rounded dark:bg-gray-700"></div>
    </div>

    <!-- Data -->
    <div v-else-if="activities.length > 0">
      <div class="backdrop-blur-sm bg-white/80 border border-gray-200/50 rounded-xl overflow-hidden dark:bg-white/5 dark:border-white/10">
        <!-- Header only for md+ -->
        <div class="hidden md:flex bg-gradient-to-r from-gray-100 to-gray-50 text-gray-800 font-medium px-6 py-4 text-sm border-b border-gray-200 dark:from-gray-800/50 dark:to-gray-700/50 dark:text-gray-200 dark:border-white/10">
          <span class="w-2/5 flex items-center">
            <span class="w-2 h-2 bg-blue-500 rounded-full mr-2 animate-pulse"></span>Aksi
          </span>
          <span class="w-1/4 text-center text-gray-700 dark:text-gray-100">Waktu</span>
          <span class="w-1/3 text-right pr-3 text-gray-700 dark:text-gray-100">Pengguna</span>
        </div>
        
        <div class="divide-y divide-gray-200 dark:divide-white/10">
          <div
            v-for="(activity, index) in activities"
            :key="activity.id"
            class="px-6 py-4 flex flex-col md:flex-row md:items-center text-sm hover:bg-gray-100/50 transition-all duration-300 animate-fade-in-up dark:hover:bg-gray-700/15"
            :style="{ animationDelay: `${index * 50}ms` }"
          >
            <!-- Aksi -->
            <div class="flex flex-col md:flex-none w-full md:w-2/5 mb-2 md:mb-0">
              <span class="block md:hidden text-gray-500 text-xs mb-1 dark:text-gray-400">Aksi</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-blue-100 to-indigo-100 border border-blue-200 text-blue-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 cursor-pointer dark:from-blue-500/20 dark:to-indigo-500/20 dark:border-blue-400/30 dark:text-blue-300">
                {{ activity.action || 'Tidak ada aksi' }}
              </span>
            </div>

            <!-- Waktu -->
            <div class="flex flex-col md:flex-none w-full md:w-1/4 mb-2 md:mb-0 text-gray-600 dark:text-gray-300">
              <span class="block md:hidden text-gray-500 text-xs mb-1 dark:text-gray-400">Waktu</span>
              <span class="text-center md:text-center truncate">{{ formatTimeAgo(activity.created_at) }}</span>
            </div>

            <!-- Pengguna -->
            <div class="flex flex-col md:flex-none w-full md:w-1/3">
              <span class="block md:hidden text-gray-500 text-xs mb-1 dark:text-gray-400">Pengguna</span>
              <span class="inline-flex items-center px-3 py-1 rounded-full bg-gradient-to-r from-gray-100 to-gray-50 border border-gray-200 text-gray-700 text-xs font-semibold hover:scale-105 transition-transform duration-300 cursor-pointer dark:from-gray-600/50 dark:to-gray-500/50 dark:border-gray-400/30 dark:text-gray-200 md:justify-end md:flex">
                {{ activity.user?.name || 'Unknown User' }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- No Data -->
    <div v-else class="text-center py-12">
      <div class="text-6xl mb-4 opacity-50">📭</div>
      <p class="text-gray-600 text-lg dark:text-gray-300">Tidak ada aktivitas terbaru.</p>
    </div>
  </div>
</template>


<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { formatDistanceToNow } from 'date-fns'
import { id } from 'date-fns/locale'

const activities = ref([])
const isLoading = ref(true)
// const pagination = ref({}) // State pagination tidak lagi diperlukan

async function fetchActivities() { // Hapus parameter 'page'
  isLoading.value = true
  try {
    // Ubah permintaan API untuk mengambil 10 item per halaman (per_page=10)
    // Ini akan mengambil 10 data terbaru dari halaman pertama.
    const response = await axios.get(`/api/activities?per_page=10`)
    activities.value = response.data.data
    // pagination.value = response.data.meta // Baris ini tidak lagi diperlukan
  } catch (error) {
    console.error('Gagal mengambil data aktivitas:', error)
    activities.value = []
  } finally {
    isLoading.value = false
  }
}

function formatTimeAgo(dateString) {
  if (!dateString) return ''
  return formatDistanceToNow(new Date(dateString), { addSuffix: true, locale: id })
}

onMounted(() => {
  fetchActivities()
})
</script>
