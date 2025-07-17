<script setup>
defineProps({
    title: String,
    items: Array,
    icon: String,
    type: String, // 'incoming' or 'outgoing'
    emptyMessage: String,
    emptyIcon: String,
});

const getBadgeColor = (type) => {
    return type === 'incoming' 
        ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400'
        : 'bg-orange-100 dark:bg-orange-900/30 text-orange-600 dark:text-orange-400';
};

const getListColor = (type) => {
    return type === 'incoming'
        ? 'from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border-blue-200/50 dark:border-blue-700/50'
        : 'from-orange-50 to-yellow-50 dark:from-orange-900/20 dark:to-yellow-900/20 border-orange-200/50 dark:border-orange-700/50';
}
</script>

<template>
    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm p-6 md:p-8 rounded-xl shadow-lg border border-white/20 dark:border-gray-700/20 h-full flex flex-col transition-colors duration-300">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-gray-700 dark:text-gray-300 text-xl font-semibold">
                {{ title }}
            </h3>
            <div :class="getBadgeColor(type)" class="px-3 py-1 rounded-full text-sm font-medium transition-colors duration-300">
                {{ (items || []).length }} items
            </div>
        </div>
        
        <div class="space-y-4 flex-grow max-h-[30rem] overflow-y-auto pr-2 custom-scrollbar">
            <div v-if="!items || items.length === 0" class="text-center py-12 flex flex-col items-center justify-center h-full">
                <div class="text-6xl mb-4 opacity-30 dark:opacity-20">{{ emptyIcon || '🤷' }}</div>
                <p class="text-gray-500 dark:text-gray-400">
                    {{ emptyMessage || 'Tidak ada data.' }}
                </p>
            </div>
            
            <div 
                v-for="task in (items || [])" 
                :key="task.id" 
                :class="getListColor(type)"
                class="group bg-gradient-to-r p-4 rounded-lg hover:shadow-md transition-all duration-300 border hover:border-opacity-70 dark:hover:border-opacity-70"
            >
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <div class="text-lg mr-2">{{ icon }}</div>
                            <p class="font-semibold text-gray-800 dark:text-gray-200">
                                {{ task.product?.name || 'N/A' }}
                            </p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ new Date(task.created_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span :class="getBadgeColor(type)" class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold transition-colors duration-300">
                            {{ task.quantity }} unit
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom scrollbar untuk dark mode */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    @apply bg-gray-100 dark:bg-gray-700 rounded-full;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    @apply bg-gray-300 dark:bg-gray-600 rounded-full hover:bg-gray-400 dark:hover:bg-gray-500;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    @apply bg-gray-400 dark:bg-gray-500;
}
</style>