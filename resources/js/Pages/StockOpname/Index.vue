<script setup>
import { ref, watch } from 'vue'
import { Head, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import AdvancedPagination from '@/Components/AdvancedPagination.vue'
import TextInput from '@/Components/TextInput.vue'
import { debounce } from 'lodash'

// --- PROPS ---
const props = defineProps({
  products: {
    type: Object,
    required: true,
  },
  flash: {
    type: Object,
    default: () => ({}),
  },
  filters: {
    type: Object,
    default: () => ({}),
  },
  auth: Object
})

// --- STATE ---
const search = ref(typeof props.filters.search === 'string' ? props.filters.search : '')

const actualStocks = ref({})

// --- METHODS ---
const calculateDifference = (product) => {
  const actual = Number(actualStocks.value[product.id] || 0)
  const recorded = Number(product.stock)
  return actual - recorded
}

const submitAudit = (product) => {
  const actualStock = actualStocks.value[product.id] || 0
  router.post(route('stock_opname.store'), {
    product_id: product.id,
    recorded_stock: product.stock,
    actual_stock: actualStock,
    difference: calculateDifference(product),
  })
}

const updateStock = (productId) => {
  router.put(route('stock_opname.updateStock', productId))
}

// Watcher untuk pencarian
watch(search, debounce((value) => {
  router.get(route('stock_opname.index'), { search: value }, {
    preserveState: true,
    replace: true,
  })
}, 300))

// Init actual stock
props.products.data.forEach(product => {
  actualStocks.value[product.id] = product.actual_stock || 0
})
</script>

<template>
  <Head title="Stok Opname" />

  <AuthenticatedLayout>
    <template #header>
      <div class="flex justify-between items-center">
        <div>
          <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Stok Opname</h1>
          <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Kelola dan audit stok produk Anda</p>
        </div>
      </div>
    </template>

    <div class="py-8">
      <div class="container mx-auto px-6">
        <!-- Flash Message -->
        <div v-if="flash.success" class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4 dark:bg-green-900/20 dark:border-green-800">
          <div class="flex items-center">
            <svg class="w-5 h-5 text-green-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-sm text-green-800 dark:text-green-200">{{ flash.success }}</p>
          </div>
        </div>

        <!-- Search -->
        <div class="mb-6">
          <div class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
            </div>
            <TextInput v-model="search" type="text" class="block w-full h-11 pl-10 pr-10" placeholder="Cari produk..." />
            <button
              v-if="search"
              @click="search = ''"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-red-500"
            >
              &times;
            </button>
          </div>
        </div>

        <!-- Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-900">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">No</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Nama Produk</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Tercatat</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stok Fisik</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Selisih</th>
                  <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-if="products.data.length === 0">
                  <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                    Tidak ada produk ditemukan
                  </td>
                </tr>
                <tr v-for="(product, index) in products.data" :key="product.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 text-sm text-center text-gray-900 dark:text-white">
                    {{ (products.current_page - 1) * products.per_page + index + 1 }}
                  </td>
                  <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                    {{ product.name }}
                  </td>
                  <td class="px-6 py-4 text-sm text-center">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                      {{ product.stock }}
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-center">
                    <input
                      type="number"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-center"
                      v-model.number="actualStocks[product.id]"
                      min="0"
                    />
                  </td>
                  <td class="px-6 py-4 text-sm text-center">
                    <span :class="[ 
                      'inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium',
                      calculateDifference(product) > 0
                        ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300'
                        : calculateDifference(product) < 0
                        ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300'
                        : 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-white'
                    ]">
                      <template v-if="calculateDifference(product) === 0">
                        <svg class="w-4 h-4 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                        <span>Sesuai</span>
                      </template>
                      <template v-else>
                        <span>{{ calculateDifference(product) > 0 ? '+' : '' }}{{ calculateDifference(product) }}</span>
                      </template>
                    </span>
                  </td>
                  <td class="px-6 py-4 text-sm text-center">
                 <div class="flex items-center justify-center gap-2">
  <SecondaryButton
    @click="submitAudit(product)"
    class="text-xs px-3 py-1.5"
  >
    Simpan
  </SecondaryButton>

  <PrimaryButton
    @click="updateStock(product.id)"
    class="text-xs px-3 py-1.5"
  >
    Update Stok
  </PrimaryButton>
</div>

                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Pagination -->
        <AdvancedPagination :pagination="products" />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
