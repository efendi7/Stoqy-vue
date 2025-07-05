import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                'mova': ['Mova', 'sans-serif'],
            },
            // Pindahkan colors ke dalam extend dan pastikan tidak override default colors
            colors: {
                // Gunakan nama yang tidak konflik dengan default Tailwind
                'app-primary': '#3b82f6',
                'app-secondary': '#1e40af',
                'app-accent': '#f59e0b',
                'app-dark': '#0f172a',
                'app-dark-light': '#1e293b',
                // Atau bisa juga extend default colors
                primary: {
                    50: '#eff6ff',
                    100: '#dbeafe',
                    500: '#3b82f6',
                    600: '#2563eb',
                    700: '#1d4ed8',
                    800: '#1e40af',
                    900: '#1e3a8a',
                }
            },
            animation: {
                'float': 'float 6s ease-in-out infinite',
                'pulse-slow': 'pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'slide-up': 'slideUp 0.5s ease-out',
                'fade-in': 'fadeIn 0.6s ease-out',
                'fade-in-up': 'slideUp 0.8s ease-out forwards',
                'bounce-slow': 'bounce 2s infinite',
            },
            keyframes: {
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-10px)' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(30px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                bounce: {
                    '0%, 100%': { 
                        transform: 'translateY(-10%)', 
                        'animation-timing-function': 'cubic-bezier(0.8, 0, 1, 1)' 
                    },
                    '50%': { 
                        transform: 'none', 
                        'animation-timing-function': 'cubic-bezier(0, 0, 0.2, 1)' 
                    },
                }
            }
        },
    },
    safelist: [
        // Text colors yang digunakan di metric cards
        'text-green-700', 'dark:text-green-300',
        'text-blue-700', 'dark:text-blue-300', 
        'text-yellow-700', 'dark:text-yellow-300',
        'text-red-700', 'dark:text-red-300',
        'text-indigo-700', 'dark:text-indigo-300',
        'text-purple-700', 'dark:text-purple-300',
        'text-teal-700', 'dark:text-teal-300',
        'text-emerald-700', 'dark:text-emerald-300',
        
        // Border colors
        'border-green-200', 'dark:border-green-400/30',
        'border-blue-200', 'dark:border-blue-400/30',
        'border-yellow-200', 'dark:border-yellow-400/30',
        'border-red-200', 'dark:border-red-400/30',
        'border-indigo-200', 'dark:border-indigo-400/30',
        'border-purple-200', 'dark:border-purple-400/30',
        'border-teal-200', 'dark:border-teal-400/30',
        'border-emerald-200', 'dark:border-emerald-400/30',
        
        // Gradient backgrounds
        'from-green-100', 'to-emerald-100', 'dark:from-green-500/20', 'dark:to-emerald-500/20',
        'from-blue-100', 'to-cyan-100', 'dark:from-blue-500/20', 'dark:to-cyan-500/20',
        'from-yellow-100', 'to-orange-100', 'dark:from-yellow-500/20', 'dark:to-orange-500/20',
        'from-red-100', 'to-pink-100', 'dark:from-red-500/20', 'dark:to-pink-500/20',
        'from-indigo-100', 'to-purple-100', 'dark:from-indigo-500/20', 'dark:to-purple-500/20',
        'from-purple-100', 'to-pink-100', 'dark:from-purple-500/20', 'dark:to-pink-500/20',
        'from-teal-100', 'to-cyan-100', 'dark:from-teal-500/20', 'dark:to-cyan-500/20',
        'from-emerald-100', 'to-green-100', 'dark:from-emerald-500/20', 'dark:to-green-500/20',
    ],
    plugins: [],
};