/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Pastikan pakai mode class, bukan 'media'
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
        "./node_modules/flowbite/**/*.js",
    ],
    theme: {
        extend: {},
    },
    plugins: ["flowbite/plugin"],
};
