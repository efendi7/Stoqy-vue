<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @font-face {
            font-family: 'CentraNo2-Bold';
            src: url('/assets/fonts/CentraNo2-Bold.ttf') format('truetype');
        }
        
        @font-face {
            font-family: 'CentraNo2-Book';
            src: url('/assets/fonts/CentraNo2-Book.ttf') format('truetype');
        }
        
        @font-face {
            font-family: 'CentraNo2-Medium';
            src: url('/assets/fonts/CentraNo2-Medium.ttf') format('truetype');
        }

        .bg-cover-custom {
            background-image: url('{{ asset('img/background.png') }}');
            background-size: cover;
            background-position: center;
        }
        @keyframes updown {
            0% {
                transform: translateY(-20px);
            }
            50% {
                transform: translateY(20px);
            }
            100% {
                transform: translateY(-20px);
            }
        }
        .animation-updown {
            animation: updown 3s linear infinite;
        }
        /* Tambahkan gaya untuk semua paragraf */
       
    </style>
</head>
<body class="bg-cover-custom flex flex-col items-center justify-center min-h-screen">

    <!-- Hero Section -->
    <header class="container mx-auto p-8 text-center">
        <div class="flex flex-col md:flex-row items-center">
            <div class="flex-1 md:w-7/12 text-center md:text-left">
                <h1 class="text-4xl font-bold mb-6 text-white">Welcome to Stockify</h1>
                <p class="text-slate-300 text-lg mb-6" style="font-family: 'CentraNo2-Book', sans-serif;">
                    Stockify adalah aplikasi web yang dirancang untuk membantu bisnis, khususnya yang memiliki gudang, dalam mengelola stok barang secara efisien dan akurat.
                </p>
                <a href="{{ route('products.index') }}" class="inline-block bg-blue-500 text-white py-3 px-6 rounded-full shadow-neumorphism focus:outline-none focus:ring-4 focus:ring-blue-300 transition duration-300 ease-in-out transform hover:scale-105">
                    Ayo Kelola Stok Barangmu
                </a>
            </div>
            <div class="flex-1 mt-8 md:mt-0 md:w-5/12 flex justify-center md:justify-end">
                <img src="{{ asset('img/Ordering Groceries Online.png') }}" alt="Header illustration of a developer" class="animation-updown w-full h-auto max-w-lg" />
            </div>
        </div>
    </header>

    <!-- About Us -->
    <section id="about-us" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Tentang Kami</h2>
        <p class="text-slate-300 mb-6 style="font-family: 'CentraNo2-Book', sans-serif;">
            Kami adalah tim yang berkomitmen untuk memberikan solusi terbaik dalam manajemen stok barang bagi bisnis Anda. Dengan pengalaman bertahun-tahun, kami siap membantu Anda mengelola stok barang secara efektif.
        </p>
    </section>

    <!-- Services -->
    <section id="services" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Layanan Kami</h2>
        <p class="text-slate-300 mb-6">
            Kami menawarkan berbagai layanan untuk membantu Anda mengelola stok barang, termasuk manajemen inventaris, pelaporan stok real-time, dan integrasi dengan sistem POS.
        </p>
    </section>

    <!-- Portfolio/Gallery -->
    <section id="portfolio" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Portofolio</h2>
        <p class="text-slate-300 mb-6">
            Berikut adalah beberapa proyek yang telah kami selesaikan:
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <img src="{{ asset('img/project1.jpg') }}" alt="Project 1" class="w-full h-auto">
            <img src="{{ asset('img/project2.jpg') }}" alt="Project 2" class="w-full h-auto">
            <img src="{{ asset('img/project3.jpg') }}" alt="Project 3" class="w-full h-auto">
        </div>
    </section>

    <!-- Pricing Plans -->
    <section id="pricing" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Paket Harga</h2>
        <p class="text-slate-300 mb-6">
            Kami menawarkan berbagai paket harga yang sesuai dengan kebutuhan bisnis Anda.
        </p>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Paket Dasar</h3>
                <p class="text-gray-700 mb-4">Fitur dasar untuk usaha kecil</p>
                <p class="text-gray-700 font-bold mb-4">$10/bulan</p>
                <a href="#" class="bg-blue-500 text-white py-2 px-4 rounded-full">Pilih Paket</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Paket Pro</h3>
                <p class="text-gray-700 mb-4">Fitur lanjutan untuk bisnis menengah</p>
                <p class="text-gray-700 font-bold mb-4">$20/bulan</p>
                <a href="#" class="bg-blue-500 text-white py-2 px-4 rounded-full">Pilih Paket</a>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <h3 class="text-xl font-bold mb-4">Paket Premium</h3>
                <p class="text-gray-700 mb-4">Semua fitur untuk bisnis besar</p>
                <p class="text-gray-700 font-bold mb-4">$30/bulan</p>
                <a href="#" class="bg-blue-500 text-white py-2 px-4 rounded-full">Pilih Paket</a>
            </div>
        </div>
    </section>

    <!-- FAQs -->
    <section id="faqs" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">FAQs</h2>
        <div class="text-slate-300 mb-6">
            <p><strong>Q:</strong> Bagaimana cara menghubungi layanan pelanggan?</p>
            <p><strong>A:</strong> Anda dapat menghubungi kami melalui email di support@stockify.com atau melalui formulir kontak di bawah ini.</p>
            <p><strong>Q:</strong> Apakah ada versi gratis?</p>
            <p><strong>A:</strong> Ya, kami menawarkan versi gratis dengan fitur dasar.</p>
        </div>
    </section>

    <!-- Blog/Articles -->
    <section id="blog" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Blog/Artikel</h2>
        <p class="text-slate-300 mb-6">
            Baca artikel terbaru kami untuk mendapatkan tips dan trik tentang manajemen stok barang.
        </p>
    </section>

    <!-- Contact Form -->
    <section id="contact" class="container mx-auto p-8 text-center">
        <h2 class="text-2xl font-bold mb-4 text-white">Hubungi Kami</h2>
        <form action="{{ route('contact.submit') }}" method="post" class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nama:</label>
                <input type="text" id="name" name="name" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-bold mb-2">Email:</label>
                <input type="email" id="email" name="email" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="mb-4">
                <label for="message" class="block text-gray-700 font-bold mb-2">Pesan:</label>
                <textarea id="message" name="message" rows="4" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-full">Kirim</button>
        </form>
    </section>

    <!-- Footer -->
    <footer class="container mx-auto p-8 text-center">
        <p class="text-slate-300 mb-6">
            &copy; 2025 Stockify. All rights reserved.
        </p>
    </footer>

    <script src="https://unpkg.com/flowbite@1.4.0/dist/flowbite