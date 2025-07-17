<script setup>
import { ref, watch, computed } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import { debounce } from 'lodash';

// Komponen ini menerima seluruh objek pagination dari Laravel
const props = defineProps({
    pagination: {
        type: Object,
        required: true,
    },
});

// State untuk pilihan "per page", diinisialisasi dari props
const perPage = ref(props.pagination.per_page);

// State untuk input "go to page", diinisialisasi dari props
const gotoPage = ref(props.pagination.current_page);

// ✅ PERBAIKAN: Sinkronkan state lokal jika props dari server berubah.
// Ini memastikan input selalu menampilkan data yang benar, bahkan setelah navigasi lain.
watch(() => props.pagination.per_page, (newValue) => {
    perPage.value = newValue;
});

watch(() => props.pagination.current_page, (newValue) => {
    gotoPage.value = newValue;
});


// Watcher untuk mengubah jumlah item per halaman
watch(perPage, (newValue) => {
    router.get(
        props.pagination.path,
        { per_page: newValue },
        {
            preserveState: true,
            replace: true,
        }
    );
});

// ✅ PERBAIKAN: Watcher untuk "go to page" kini menyertakan parameter `per_page`.
watch(gotoPage, debounce((newValue) => {
    const page = parseInt(newValue, 10);
    if (page > 0 && page <= props.pagination.last_page && page !== props.pagination.current_page) {
        router.get(
            props.pagination.path,
            {
                page: page,
                // Ini adalah perbaikan utamanya: selalu sertakan nilai per_page yang aktif
                per_page: perPage.value
            },
            {
                preserveState: true,
                replace: true,
            }
        );
    }
}, 500)); // Delay 500ms setelah user berhenti mengetik

// Computed property untuk membuat link halaman lebih ringkas jika terlalu banyak
const computedLinks = computed(() => {
    const links = props.pagination.links;
    if (links.length <= 7) {
        return links;
    }

    const { current_page, last_page } = props.pagination;
    const firstLink = links[0];
    const lastLink = links[links.length - 1];
    const surroundingLinks = [];
    
    // Selalu tampilkan 2 halaman sebelum dan 2 halaman setelah halaman saat ini
    let start = Math.max(2, current_page - 2);
    let end = Math.min(last_page - 1, current_page + 2);

    // Penyesuaian agar selalu ada 5 link angka jika memungkinkan
    if (end - start < 4) {
        if (current_page < (last_page / 2)) {
            end = Math.min(last_page - 1, start + 4);
        } else {
            start = Math.max(2, end - 4);
        }
    }

    for (let i = start; i <= end; i++) {
        surroundingLinks.push(links[i]);
    }

    const newLinks = [firstLink]; // Selalu ada link halaman pertama
    if (start > 2) newLinks.push({ url: null, label: '...', active: false });
    newLinks.push(...surroundingLinks);
    if (end < last_page - 1) newLinks.push({ url: null, label: '...', active: false });
    newLinks.push(lastLink); // Selalu ada link halaman terakhir

    return newLinks;
});
</script>

<template>
    <div v-if="pagination && pagination.total > 0" class="flex items-center justify-between py-3 px-4 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 sm:rounded-b-lg">
        
        <div class="text-sm text-gray-700 dark:text-gray-400 hidden sm:block">
            Menampilkan <span class="font-medium">{{ pagination.from || 0 }}</span> - <span class="font-medium">{{ pagination.to || 0 }}</span> dari <span class="font-medium">{{ pagination.total || 0 }}</span> hasil
        </div>

        <div class="flex-1 flex justify-center sm:justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <template v-for="(link, index) in computedLinks" :key="index">
                    <div
                        v-if="link.url === null"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm font-medium text-gray-400 cursor-default"
                        v-html="link.label"
                    />
                    <Link
                        v-else
                        :href="link.url + '&per_page=' + perPage"
                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors"
                        :class="{
                            'z-10 bg-blue-50 border-blue-500 text-blue-600 dark:bg-gray-900 dark:border-blue-400 dark:text-blue-300': link.active,
                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700': !link.active
                        }"
                        v-html="link.label"
                        preserve-scroll
                    />
                </template>
            </nav>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ml-6 space-x-4">
            <div class="flex items-center">
                <label for="per_page" class="mr-2 text-sm text-gray-700 dark:text-gray-400">Per Halaman:</label>
                <select id="per_page" v-model="perPage" class="block w-full pl-3 pr-8 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
                    <option>5</option>
                    <option>10</option>
                    <option>25</option>
                    <option>50</option>
                    <option>100</option>
                </select>
            </div>
             <div class="flex items-center">
                <label for="goto_page" class="mr-2 text-sm text-gray-700 dark:text-gray-400">Ke Hal:</label>
                <input type="number" id="goto_page" v-model.lazy="gotoPage" :max="pagination.last_page" min="1" class="w-20 pl-3 pr-1 py-2 text-base border-gray-300 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm rounded-md dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300">
            </div>
        </div>
    </div>
</template>