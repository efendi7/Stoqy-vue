<script setup>
import { Link } from '@inertiajs/vue3';

// Komponen ini menerima 'links' sebagai prop dari data pagination Laravel
defineProps({
    links: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, index) in links" :key="index">
                <div
                    v-if="link.url === null"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                    v-html="link.label"
                />

                <Link
                    v-else
                    :href="link.url"
                    class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-700"
                    :class="{ 'bg-blue-700 text-white dark:bg-blue-800': link.active }"
                    v-html="link.label"
                />
            </template>
        </div>
    </div>
</template>