<template>
  <section class="relative overflow-hidden mb-8 group animate-fade-in-up">
    <div class="relative z-10 flex flex-col items-center justify-center text-center w-full h-full p-8 md:p-16 transform hover:scale-[1.02] transition-all duration-700 ease-out">
      <h2 class="text-4xl md:text-5xl lg:text-6xl font-black mb-4 leading-tight">
        <span class="block bg-gradient-to-r from-teal-500 via-teal-600 to-emerald-600 bg-clip-text text-transparent animate-gradient-x dark:from-teal-300 dark:via-teal-400 dark:to-emerald-400">
          Selamat Datang
        </span>
        <span class="block text-2xl md:text-3xl lg:text-4xl font-semibold text-gray-800 mt-2 capitalize dark:text-gray-100 transition-colors duration-300">
          {{ userRoleLabel }}, {{ userName }}!
        </span>
      </h2>
      
      <p class="text-gray-600 text-lg md:text-xl max-w-2xl leading-relaxed mb-6 dark:text-gray-300 transition-colors duration-300">
        Mari kelola inventaris Anda dengan mudah dan efisien. 
        <span class="text-teal-600 font-medium dark:text-teal-400 transition-colors duration-300">Dashboard yang powerful</span> 
        untuk kebutuhan bisnis Anda.
      </p>
      
     
    </div>
  </section>
</template>

<script setup>
import { defineProps } from 'vue';

// Props dari komponen induk
defineProps({
  userRoleLabel: {
    type: String,
    default: 'Pengguna',
  },
  userName: {
    type: String,
    default: 'User',
  },
});
</script>

<style scoped>
/* Custom animations */
@keyframes gradient-x {
  0%, 100% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
}

@keyframes fade-in-up {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-gradient-x {
  background-size: 200% 200%;
  animation: gradient-x 3s ease infinite;
}

.animate-fade-in-up {
  animation: fade-in-up 0.8s ease-out;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  h2 {
    font-size: 2.5rem;
  }
  
  h2 span:last-child {
    font-size: 1.5rem;
  }
  
  p {
    font-size: 1rem;
  }
}
</style>