// tailwind.config.js
/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // <--- PENTING: Aktifkan dark mode berbasis kelas
    content: [
        // ... jalur ke file Blade Anda
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                primary: '#3b82f6',
                secondary: '#1e40af',
                accent: '#f59e0b',
                dark: '#0f172a',
                'dark-light': '#1e293b'
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
                slideUp: {
                    '0%': { transform: 'translateY(30px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                bounce: {
                    '0%, 100%': { transform: 'translateY(-10%)', 'animation-timing-function': 'cubic-bezier(0.8, 0, 1, 1)' },
                    '50%': { transform: 'none', 'animation-timing-function': 'cubic-bezier(0, 0, 0.2, 1)' },
                }
            }
        },
    },
    plugins: [],
};