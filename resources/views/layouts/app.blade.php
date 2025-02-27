<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0 {{ csrf_token() }}">
    <title>@yield('title', 'My Laravel App')</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div id="app">
        @include('partials.navbar')
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @vite('resources/js/app.js')
    <script>
document.addEventListener("DOMContentLoaded", function () {
    const darkModeToggle = document.getElementById("dark-mode-toggle");
    const htmlElement = document.documentElement;

    // Cek apakah dark mode sudah diaktifkan sebelumnya
    if (localStorage.getItem("dark-mode") === "enabled") {
        htmlElement.classList.add("dark");
    }

    darkModeToggle.addEventListener("click", function () {
        htmlElement.classList.toggle("dark");

        // Simpan preferensi ke localStorage
        if (htmlElement.classList.contains("dark")) {
            localStorage.setItem("dark-mode", "enabled");
        } else {
            localStorage.setItem("dark-mode", "disabled");
        }
    });
});
</script>

</body>
</html>
