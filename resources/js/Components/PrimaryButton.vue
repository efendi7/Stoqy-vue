<template>
    <button
        class="inline-flex items-center rounded-md border border-transparent 
               bg-gray-800 text-white 
               dark:bg-white dark:text-gray-800
               px-4 py-2 text-xs font-semibold uppercase tracking-widest 
               transition duration-150 ease-in-out 
               hover:bg-gray-700 dark:hover:bg-gray-100
               focus:bg-gray-700 dark:focus:bg-gray-100
               focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 
               active:bg-gray-900 dark:active:bg-gray-200"
    >
        <slot />
    </button>
</template>
