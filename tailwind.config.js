export default {
    // Hapus atau komentar darkMode
    // darkMode: , // Nonaktifkan dark mode
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
