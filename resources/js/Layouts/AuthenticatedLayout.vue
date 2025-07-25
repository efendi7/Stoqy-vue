<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import TheNavbar from '@/Layouts/TheNavbar.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import TheSidebar from '@/Layouts/TheSidebar.vue';
import TheFooter from '@/Layouts/TheFooter.vue';

const isSidebarOpen = ref(false);
const isMobileNavOpen = ref(false);
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Sidebar diposisikan secara independen dan tidak mengganggu alur utama -->
        <TheSidebar
            :settings="$page.props.settings"
            :user="$page.props.auth.user"
            :pending-requests="$page.props.pendingRequests"
            :is-sidebar-open="isSidebarOpen"
            @close="isSidebarOpen = false"
        />

        <!-- Wrapper ini menangani area konten utama, termasuk memberi ruang untuk sidebar -->
        <!-- Menggunakan flex-col untuk menyusun anak-anaknya (konten dan footer) secara vertikal -->
        <div class="lg:pl-64 flex flex-col min-h-screen">
            
            <!-- Kontainer untuk konten utama ini akan "tumbuh" untuk mengisi ruang yang tersedia -->
            <div class="flex-grow">
                <TheNavbar
                    :settings="$page.props.settings"
                    :user="$page.props.auth.user"
                    :pending-requests="$page.props.pendingRequests"
                    @toggle-sidebar="isSidebarOpen = !isSidebarOpen"
                />

                <!-- Sisa struktur halaman dari layout asli Anda -->
                <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex">
                                <div class="shrink-0 flex items-center lg:hidden">
                                    <Link :href="route('dashboard')">
                                        <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200" />
                                    </Link>
                                </div>
                                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                    <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                        Dashboard
                                    </NavLink>
                                </div>
                            </div>
                            <div class="hidden sm:flex sm:items-center sm:ms-6">
                                <div class="ms-3 relative">
                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                                    {{ $page.props.auth.user.name }}
                                                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>
                                        <template #content>
                                            <DropdownLink :href="route('profile.edit')">Profile</DropdownLink>
                                            <DropdownLink :href="route('logout')" method="post" as="button">
                                                Log Out
                                            </DropdownLink>
                                        </template>
                                    </Dropdown>
                                </div>
                            </div>
                            <div class="-me-2 flex items-center sm:hidden">
                                <button @click="isMobileNavOpen = !isMobileNavOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path :class="{ hidden: isMobileNavOpen, 'inline-flex': !isMobileNavOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                        <path :class="{ hidden: !isMobileNavOpen, 'inline-flex': isMobileNavOpen }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div :class="{ block: isMobileNavOpen, hidden: !isMobileNavOpen }" class="sm:hidden">
                        <div class="pt-2 pb-3 space-y-1">
                            <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                Dashboard
                            </ResponsiveNavLink>
                        </div>
                        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                            <div class="px-4">
                                <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <ResponsiveNavLink :href="route('profile.edit')">Profile</ResponsiveNavLink>
                                <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                    Log Out
                                </ResponsiveNavLink>
                            </div>
                        </div>
                    </div>
                </nav>

                <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Area konten utama. Padding-bottom telah dihapus. -->
                <main>
                    <slot />
                </main>
            </div>

            <!-- Footer tidak lagi fixed. Ia adalah bagian dari flex-column dan tidak akan membesar/menyusut. -->
            <TheFooter
                app-name="STOQY"
                app-version="1.0.0"
                class="shrink-0"
            />
        </div>
    </div>
</template>
