<template>
  <aside 
    id="sidebar"
    :class="[
      'fixed top-0 left-0 h-full w-64 transform transition-transform duration-300 ease-in-out z-40',
      // Light Mode Styles (disesuaikan dengan tema teal)
      'bg-teal-50 text-teal-800 border-r border-teal-200', 
      // Dark Mode Styles (tetap sama)
      'dark:bg-[#161B22]/80 dark:text-gray-300 dark:backdrop-blur-lg dark:border-r dark:border-white/10',
      isSidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
  >
    <div class="p-4 pt-6 h-full flex flex-col">
      <div class="flex items-center space-x-3 mb-8 px-2">
        <img v-if="settings?.logo" :src="`/storage/${settings.logo}`" class="w-10 h-10 rounded-lg shadow-md" alt="Logo">
        <span class="font-mova font-bold text-xl text-teal-800 dark:text-white">{{ settings?.app_name || 'Nama App' }}</span>
      </div>

      <button @click="$emit('close')" class="absolute top-4 right-4 text-teal-600 hover:text-teal-800 dark:text-gray-400 dark:hover:text-white lg:hidden">
        <i class="fas fa-times text-2xl"></i>
      </button>

      <Scrollbar>
        <ul class="space-y-1 pb-4">
          <li class="px-3 pt-4 pb-2">
            <span class="text-xs font-semibold text-teal-600/80 dark:text-gray-500 uppercase tracking-wider">Utama</span>
          </li>
          <SidebarLink :href="route('dashboard')" :active="$page.url.startsWith('/dashboard')" icon="fa-tachometer-alt">
            Dashboard
          </SidebarLink>
          <SidebarLink v-if="user?.role === 'admin'" :href="route('admin.role-requests')" :active="$page.url.startsWith('/admin/role-requests')" icon="fa-user-check" :badge="pendingRequests">
            Permintaan Role
          </SidebarLink>

          <template v-if="user && ['admin', 'warehouse_manager'].includes(user.role)">
            <li class="px-3 pt-4 pb-2">
              <span class="text-xs font-semibold text-teal-600/80 dark:text-gray-500 uppercase tracking-wider">Manajemen</span>
            </li>
            <li>
              <button @click="toggleDropdown('produk')" class="flex items-center justify-between w-full p-3 rounded-lg group text-teal-700 hover:bg-teal-100/60 dark:text-gray-300 dark:hover:bg-white/5 transition duration-200">
                <span class="flex items-center">
                  <i class="fas fa-cube w-6 text-center mr-3 text-lg text-teal-600 dark:text-gray-400 group-hover:text-teal-700 dark:group-hover:text-white"></i>
                  <span class="font-medium">Produk</span>
                </span>
                <i :class="['fas fa-chevron-down text-xs text-teal-600 dark:text-gray-400 transform transition-transform duration-300', { 'rotate-180': openDropdown === 'produk' }]"></i>
              </button>
              <div v-show="openDropdown === 'produk'" class="pl-8 mt-1 space-y-1">
                <Link :href="route('products.index')" :class="getDropdownLinkClass('/products')">Daftar Produk</Link>
                <Link :href="route('categories.index')" :class="getDropdownLinkClass('/categories')">Kategori Produk</Link>
                <Link :href="route('product_attributes.index')" :class="getDropdownLinkClass('/product_attributes')">Atribut Produk</Link>
              </div>
            </li>
            <SidebarLink :href="route('suppliers.index')" :active="$page.url.startsWith('/suppliers')" icon="fa-truck">Penyuplai</SidebarLink>
            <SidebarLink :href="route('stock_opname.index')" :active="$page.url.startsWith('/stock_opname')" icon="fa-clipboard-list">Stok Opname</SidebarLink>
          </template>

          <SidebarLink v-if="user" :href="route('stock_transactions.index')" :active="$page.url.startsWith('/stock_transactions')" icon="fa-exchange-alt">
            Transaksi Stok
          </SidebarLink>
          
          <li v-if="user && ['admin', 'warehouse_manager'].includes(user.role)">
              <button @click="toggleDropdown('laporan')" class="flex items-center justify-between w-full p-3 rounded-lg group text-teal-700 hover:bg-teal-100/60 dark:text-gray-300 dark:hover:bg-white/5 transition duration-200">
                <span class="flex items-center">
                    <i class="fas fa-file-alt w-6 text-center mr-3 text-lg text-teal-600 dark:text-gray-400 group-hover:text-teal-700 dark:group-hover:text-white"></i>
                    <span class="font-medium">Laporan</span>
                </span>
                <i :class="['fas fa-chevron-down text-xs text-teal-600 dark:text-gray-400 transform transition-transform duration-300', { 'rotate-180': openDropdown === 'laporan' }]"></i>
              </button>
              <div v-show="openDropdown === 'laporan'" class="pl-8 mt-1 space-y-1">
                <Link :href="route('laporan.stok.index')" :class="getDropdownLinkClass('/products')">Laporan Stok</Link>
                <Link :href="route('laporan.aktivitas.index')" :class="getDropdownLinkClass('/categories')">Laporan Aktvitas</Link>
               
                  </div>
          </li>
          
          <template v-if="user?.role === 'admin'">
             <li class="px-3 pt-4 pb-2">
               <span class="text-xs font-semibold text-teal-600/80 dark:text-gray-500 uppercase tracking-wider">Sistem</span>
            </li>
            <SidebarLink :href="route('users.index')" :active="$page.url.startsWith('/users')" icon="fa-users">Pengguna</SidebarLink>
            <SidebarLink :href="route('settings.index')" :active="$page.url.startsWith('/settings')" icon="fa-cog">Pengaturan</SidebarLink>
          </template>
        </ul>
      </Scrollbar>

      <div class="mt-auto pt-4 border-t border-teal-200 dark:border-white/10">
        <Link as="button" method="post" :href="route('logout')" class="flex items-center w-full p-3 rounded-lg group text-red-600 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-500/20 dark:hover:text-red-300 transition duration-200">
          <i class="fas fa-sign-out-alt w-6 text-center mr-3 text-lg"></i>
          <span class="font-medium">Sign out</span>
        </Link>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import SidebarLink from '@/Layouts/SidebarLink.vue';
import Scrollbar from '@/Components/Scrollbar.vue';

// PROPS (tidak berubah)
defineProps({
  settings: {
    type: Object,
    default: () => ({
      logo: null,
      app_name: 'Nama App'
    })
  },
  user: {
    type: Object,
    default: () => null
  },
  pendingRequests: {
    type: Number,
    default: 0
  },
  isSidebarOpen: {
    type: Boolean,
    default: false
  }
});

// EMITS (tidak berubah)
defineEmits(['close']);

// STATE (tidak berubah)
const openDropdown = ref(null);
const page = usePage();

// Logika dropdown (tidak berubah)
const isProdukActive = computed(() => page.url.startsWith('/products') || page.url.startsWith('/categories') || page.url.startsWith('/product_attributes'));
if (isProdukActive.value) {
    openDropdown.value = 'produk';
}
function toggleDropdown(menu) {
  openDropdown.value = openDropdown.value === menu ? null : menu;
}


// METHODS: Fungsi untuk interaksi (disesuaikan dengan tema teal)
function getDropdownLinkClass(url) {
  const commonClass = 'block p-2 text-sm rounded-md transition duration-200';
  
  if (page.url.startsWith(url)) {
    // Kelas untuk link aktif (light & dark mode)
    return `${commonClass} bg-teal-100 text-teal-700 dark:bg-white/10 dark:text-white font-semibold`;
  }
  
  // Kelas untuk link tidak aktif (light & dark mode)
  return `${commonClass} text-teal-700 hover:bg-teal-100/60 hover:text-teal-800 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-white`;
}
</script>