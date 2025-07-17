<template>
  <nav class="fixed top-0 left-0 lg:left-64 w-full lg:w-[calc(100%-16rem)] z-30 transition-all duration-300 
             bg-teal-50 border-b border-teal-200 shadow-sm
             dark:bg-[#161B22]/80 dark:backdrop-blur-lg dark:border-b dark:border-white/10">

    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-3 h-16">
        
        <div class="flex items-center">
          <button @click="$emit('toggle-sidebar')" class="text-teal-600 dark:text-gray-300 text-2xl focus:outline-none mr-4 hover:text-teal-800 dark:hover:text-white transition-colors duration-300">
            <i class="fas fa-bars"></i>
          </button>
          
          <Link :href="route('dashboard')" class="flex items-center space-x-2 lg:hidden">
            <img v-if="settings?.logo" :src="`/storage/${settings.logo}`" class="w-8 h-8 rounded" alt="Logo">
            <span class="text-teal-800 dark:text-gray-100 font-bold text-lg">{{ settings?.app_name || 'App' }}</span>
          </Link>
        </div>

        <div class="flex items-center space-x-4 ml-auto">
          <DarkmodeToggle />

          <div v-if="user" class="relative" ref="userDropdownContainer">
            <button @click="isUserDropdownOpen = !isUserDropdownOpen" class="flex items-center space-x-2 bg-teal-100 dark:bg-white/10 px-3 py-1 rounded-lg hover:bg-teal-200 dark:hover:bg-white/20 transition focus:outline-none hover:scale-105 duration-300">
              <img class="w-8 h-8 rounded-full object-cover" :src="user.profile_picture ? `/storage/${user.profile_picture}` : '/img/logofenn.png'" alt="user photo">
              <span class="text-teal-700 dark:text-gray-100">{{ user.name }}</span>
              <i class="fas fa-chevron-down text-teal-500 dark:text-gray-400 text-xs"></i>
            </button>

            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div v-if="isUserDropdownOpen" class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-700 shadow-lg rounded-md z-50 border dark:border-gray-600">
                <div class="px-4 py-3 border-b bg-teal-50 dark:bg-gray-800 border-teal-200 dark:border-gray-600">
                  <p class="text-sm font-semibold text-teal-800 dark:text-gray-100">{{ user.name }}</p>
                  <p class="text-xs text-teal-600 dark:text-gray-300 truncate">{{ user.email }}</p>
                  <p class="text-xs text-teal-500 dark:text-gray-400 capitalize">{{ user.role_label }}</p>
                </div>
                <ul class="py-1">
                  <li>
                    <Link :href="route('profile.edit')" class="block px-4 py-2 text-sm text-teal-700 dark:text-gray-200 hover:bg-teal-100 dark:hover:bg-gray-600">
                      <i class="fas fa-user w-6 mr-1"></i>Profil Saya
                    </Link>
                  </li>
                  <li>
                    <Link :href="route('dashboard')" class="block px-4 py-2 text-sm text-teal-700 dark:text-gray-200 hover:bg-teal-100 dark:hover:bg-gray-600">
                      <i class="fas fa-tachometer-alt w-6 mr-1"></i>Dashboard
                    </Link>
                  </li>
                  <template v-if="user.role === 'admin'">
                    <li>
                      <Link :href="route('settings.index')" class="block px-4 py-2 text-sm text-teal-700 dark:text-gray-200 hover:bg-teal-100 dark:hover:bg-gray-600">
                        <i class="fas fa-cog w-6 mr-1"></i>Pengaturan
                      </Link>
                    </li>
                    <li>
                      <Link :href="route('admin.role-requests')" class="flex items-center justify-between px-4 py-2 text-sm text-teal-700 dark:text-gray-200 hover:bg-teal-100 dark:hover:bg-gray-600">
                        <span><i class="fas fa-user-check w-6 mr-1"></i>Permintaan Role</span>
                        <span v-if="pendingRequests > 0" class="ml-2 bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full">
                          {{ pendingRequests }}
                        </span>
                      </Link>
                    </li>
                  </template>
                  <li class="border-t border-teal-200 dark:border-gray-600 my-1">
                    <Link as="button" method="post" :href="route('logout')" class="flex items-center w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                      <i class="fas fa-sign-out-alt w-6 mr-1"></i>Sign out
                    </Link>
                  </li>
                </ul>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import DarkmodeToggle from '@/Components/DarkmodeToggle.vue';

defineProps({
  settings: Object,
  user: Object,
  pendingRequests: Number,
});

defineEmits(['toggle-sidebar']);

const isUserDropdownOpen = ref(false);
const userDropdownContainer = ref(null);

const closeDropdownOnOutsideClick = (event) => {
  if (userDropdownContainer.value && !userDropdownContainer.value.contains(event.target)) {
    isUserDropdownOpen.value = false;
  }
};

onMounted(() => {
  document.addEventListener('click', closeDropdownOnOutsideClick);
});

onUnmounted(() => {
  document.removeEventListener('click', closeDropdownOnOutsideClick);
});
</script>