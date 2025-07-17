<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen flex bg-gray-50">
        <Head title="Log in" />

        <!-- Left Side - Illustration -->
        <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
            <!-- Gradient Background -->
            <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-blue-600"></div>
            
            <!-- Decorative Elements -->
            <div class="absolute inset-0 flex items-center justify-center">
                <!-- Floating Shapes -->
                <div class="absolute top-20 left-20 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                <div class="absolute top-40 right-32 w-24 h-24 bg-white/5 rounded-full animate-bounce"></div>
                <div class="absolute bottom-32 left-16 w-12 h-12 bg-white/15 rounded-full animate-pulse"></div>
                <div class="absolute top-60 left-32 w-8 h-8 bg-white/20 rounded-full"></div>
                <div class="absolute bottom-40 right-20 w-20 h-20 bg-white/5 rounded-full animate-pulse"></div>
                
                <!-- Main Dashboard Mockup -->
                <div class="relative z-10 bg-white/10 backdrop-blur-sm rounded-2xl p-8 max-w-md mx-8 shadow-2xl">
                    <!-- Header -->
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-white text-lg font-semibold">Smart Inventory</h3>
                        <div class="flex space-x-2">
                            <div class="w-3 h-3 bg-red-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-yellow-400 rounded-full"></div>
                            <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                        </div>
                    </div>
                    
                    <!-- Stats Cards -->
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                            <div class="text-white text-xl font-bold">1,234</div>
                            <div class="text-white/80 text-xs">Total Items</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                            <div class="text-green-300 text-xl font-bold">$45,678</div>
                            <div class="text-white/80 text-xs">Revenue</div>
                        </div>
                        <div class="bg-white/20 backdrop-blur-sm rounded-lg p-3 text-center">
                            <div class="text-blue-300 text-xl font-bold">89%</div>
                            <div class="text-white/80 text-xs">Efficiency</div>
                        </div>
                    </div>
                    
                    <!-- Chart Area -->
                    <div class="bg-white/20 backdrop-blur-sm rounded-lg p-4">
                        <div class="h-20 flex items-end justify-between">
                            <div class="w-4 bg-blue-400 rounded-t" style="height: 60%"></div>
                            <div class="w-4 bg-purple-400 rounded-t" style="height: 80%"></div>
                            <div class="w-4 bg-indigo-400 rounded-t" style="height: 40%"></div>
                            <div class="w-4 bg-cyan-400 rounded-t" style="height: 90%"></div>
                            <div class="w-4 bg-blue-400 rounded-t" style="height: 70%"></div>
                            <div class="w-4 bg-purple-400 rounded-t" style="height: 50%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Text Content -->
            <div class="absolute bottom-20 left-8 right-8 text-center text-white z-10">
                <h2 class="text-3xl font-bold mb-4">Smart Inventory Management</h2>
                <p class="text-lg opacity-90 mb-8">Streamline your warehouse operations with intelligent stock management, real-time tracking, and automated reporting.</p>
                
                <!-- Progress Dots -->
                <div class="flex justify-center space-x-3">
                    <div class="w-3 h-3 bg-white rounded-full"></div>
                    <div class="w-8 h-3 bg-white/50 rounded-full"></div>
                    <div class="w-3 h-3 bg-white/30 rounded-full"></div>
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex flex-col justify-center px-8 sm:px-12 lg:px-16 bg-white">
            <div class="w-full max-w-md mx-auto">
                <!-- Logo/Brand -->
                <div class="text-center mb-8">
                    <div class="flex items-center justify-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-xl">S</span>
                        </div>
                    </div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Stockify</h1>
                    <p class="text-gray-600">Welcome back to your dashboard</p>
                </div>

                <!-- Status Message -->
                <div v-if="status" class="mb-6 p-4 text-sm font-medium text-green-600 bg-green-50 rounded-xl border border-green-200">
                    {{ status }}
                </div>

                <!-- Login Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Email/Username Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Address</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            placeholder="Enter your email"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                            required
                            autofocus
                            autocomplete="username"
                        />
                        <InputError class="mt-2" :message="form.errors.email" />
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            placeholder="Enter your password"
                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-300 bg-gray-50 focus:bg-white"
                            required
                            autocomplete="current-password"
                        />
                        <InputError class="mt-2" :message="form.errors.password" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2"
                            />
                            <span class="ml-2 text-sm text-gray-700">Remember me</span>
                        </label>
                        
                        <Link
                            v-if="canResetPassword"
                            :href="route('password.request')"
                            class="text-sm text-indigo-600 hover:text-indigo-500 transition-colors font-medium"
                        >
                            Forgot password?
                        </Link>
                    </div>

                    <!-- Sign In Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 px-4 rounded-xl hover:from-indigo-700 hover:to-purple-700 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300 font-semibold text-lg shadow-lg hover:shadow-xl transform hover:-translate-y-0.5"
                        :class="{ 'opacity-25': form.processing }"
                    >
                        <span v-if="form.processing" class="flex items-center justify-center">
                            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Signing in...
                        </span>
                        <span v-else>Sign in to Dashboard</span>
                    </button>

                    <!-- Divider -->
                    <div class="relative my-6">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-white text-gray-500">or continue with</span>
                        </div>
                    </div>

                    <!-- Google Sign In -->
                    <button
                        type="button"
                        class="w-full flex items-center justify-center px-4 py-3 border border-gray-300 rounded-xl hover:bg-gray-50 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-300 font-medium"
                    >
                        <svg class="w-5 h-5 mr-3" viewBox="0 0 24 24">
                            <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Sign in with Google
                    </button>

                    <!-- Sign Up Link -->
                    <div class="text-center pt-6 border-t border-gray-200">
                        <p class="text-sm text-gray-600">
                            Don't have an account? 
                            <a href="#" class="text-indigo-600 hover:text-indigo-500 transition-colors font-semibold">
                                Start free trial
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom animations */
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}

/* Smooth transitions */
input:focus {
    transform: translateY(-1px);
}

button:hover {
    transform: translateY(-1px);
}

/* Loading animation */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>