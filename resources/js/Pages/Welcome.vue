<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';

// --- PROPS ---
defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
});

// --- MANAJEMEN STATE ---
const isMobileMenuOpen = ref(false);
const showScrollToTopButton = ref(false);

const faqs = ref([
    {
        id: 1,
        question: 'Apa itu Stockify?',
        answer: 'Stockify adalah aplikasi berbasis web yang dirancang untuk membantu bisnis, terutama yang memiliki gudang, mengelola inventaris mereka secara efisien dan akurat. Ini menyederhanakan pelacakan produk, memperlancar transaksi, dan menyediakan laporan yang berwawasan.',
        isOpen: false
    },
    {
        id: 2,
        question: 'Apa saja fitur utama Stockify?',
        answer: 'Stockify menawarkan fitur-fitur inti seperti Manajemen Produk (CRUD produk, kategori, pemasok, atribut), Manajemen Stok (transaksi masuk/keluar, pemantauan, stock opname), Manajemen Pengguna (kontrol akses berbasis peran untuk Admin, Manajer Gudang, Staf), dan Pelaporan yang komprehensif.',
        isOpen: false
    },
    {
        id: 3,
        question: 'Teknologi apa yang digunakan untuk membangun Stockify?',
        answer: 'Stockify dibangun menggunakan Laravel 10 (PHP Framework), MySQL (Database), Tailwind CSS (Frontend Framework), Flowbite (UI Component Library), dan Flowbite Admin Dashboard (Template). Ini mengikuti pola arsitektur Controller-Service-Repository.',
        isOpen: false
    }
]);

const toggleFaq = (faqId) => {
    const faq = faqs.value.find(f => f.id === faqId);
    if (faq) {
        faq.isOpen = !faq.isOpen;
    }
};

const scrollToTop = () => {
    window.scrollTo({ top: 0, behavior: 'smooth' });
};

onMounted(() => {
    // Logika tombol Scroll-to-Top
    const handleScroll = () => {
        showScrollToTopButton.value = window.pageYOffset > 300;
    };
    window.addEventListener('scroll', handleScroll);

    // Intersection Observer untuk animasi gulir
    const animateElements = document.querySelectorAll('.animate-on-scroll');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    animateElements.forEach(element => observer.observe(element));

    // Logika efek partikel
    const particlesContainer = document.getElementById('particles-js');
    if (particlesContainer) {
        const numParticles = 30;
        // Perubahan Warna: Memperbarui warna partikel menjadi nuansa teal/cyan
        const colors = ['rgba(20, 184, 166, 0.6)', 'rgba(8, 145, 178, 0.6)', 'rgba(15, 118, 110, 0.6)'];
        for (let i = 0; i < numParticles; i++) {
            const particle = document.createElement('div');
            particle.classList.add('particle');
            const size = Math.random() * 8 + 4;
            const x = Math.random() * 100;
            const y = Math.random() * 100;
            const duration = Math.random() * 10 + 5;
            const delay = Math.random() * 5;
            const endX = (Math.random() - 0.5) * 200;
            const endY = (Math.random() - 0.5) * 200;
            const color = colors[Math.floor(Math.random() * colors.length)];
            particle.style.cssText = `
                width: ${size}px; height: ${size}px; left: ${x}%; top: ${y}%;
                animation-duration: ${duration}s; animation-delay: ${delay}s;
                background-color: ${color};
                --x-end: ${endX}px; --y-end: ${endY}px;
            `;
            particlesContainer.appendChild(particle);
        }
    }
});
</script>

<style>
/* CSS Kustom */
@keyframes float {
  0%, 100% { transform: translateY(0px); }
  50% { transform: translateY(-20px); }
}
/* Perubahan Warna: Memperbarui gradien menjadi teal/cyan */
.text-gradient {
  background: linear-gradient(135deg, #14b8a6 0%, #0891b2 100%);
  -webkit-background-clip: text;
  -webkit-text-fill-color: transparent;
  background-clip: text;
}
/* Perubahan Warna: Memperbarui gradien hero menjadi teal */
.hero-gradient { background: radial-gradient(ellipse at center, rgba(20, 184, 166, 0.1) 0%, transparent 70%); }
.card-hover { transition: all 0.3s ease; }
.card-hover:hover {
  transform: translateY(-8px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
/* Perubahan Warna: Memperbarui gradien ikon fitur menjadi teal */
.feature-icon { background: linear-gradient(135deg, #14b8a6, #0d9488); }
.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}
.animate-on-scroll.is-visible {
  opacity: 1;
  transform: translateY(0);
}
.particles-container { position: absolute; top: 0; left: 0; width: 100%; height: 100%; overflow: hidden; pointer-events: none; z-index: -1; }
.particle { position: absolute; border-radius: 50%; animation: moveParticles linear infinite; }
@keyframes moveParticles {
  0% { transform: translate(0, 0); opacity: 0; }
  50% { opacity: 1; }
  100% { transform: translate(var(--x-end), var(--y-end)); opacity: 0; }
}
</style>

<template>
    <Head title="Stockify - Manajemen Inventaris Cerdas" />
    
    <div class="bg-teal-50 dark:bg-gray-900 text-gray-800 dark:text-gray-300">
        
        <Transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-active-class="transition ease-in duration-300" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <button v-show="showScrollToTopButton" @click="scrollToTop" class="fixed bottom-6 right-6 bg-teal-600 text-white p-3 rounded-full shadow-lg hover:bg-teal-700 transition-colors focus:outline-none z-50">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
            </button>
        </Transition>

        <nav class="fixed w-full top-0 z-50 bg-white/80 dark:bg-gray-900/80 backdrop-blur-md border-b border-teal-200 dark:border-white/10">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center animate-bounce-slow">
                                <span class="text-white font-bold text-xl">S</span>
                            </div>
                        </div>
                        <div class="ml-3">
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">Stockify</span>
                        </div>
                    </div>

                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-8">
                            <a href="#features" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">Fitur</a>
                            <a href="#pricing" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">Harga</a>
                            <a href="#faq" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors">FAQ</a>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <Link v-if="canLogin" :href="route('login')" class="text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 transition-colors hidden md:block">Masuk</Link>
                        <Link v-if="canRegister" :href="route('register')" class="bg-teal-600 text-white px-6 py-2 rounded-full hover:bg-teal-700 transition-colors hidden md:block">Mulai</Link>
                        <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-700 dark:text-gray-400 hover:text-teal-600 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none md:hidden">
                            <span class="sr-only">Buka menu utama</span>
                            <svg v-if="!isMobileMenuOpen" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                            <svg v-else class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div v-show="isMobileMenuOpen" class="md:hidden bg-white dark:bg-gray-900 border-t border-teal-200 dark:border-white/10">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                    <a href="#features" @click="isMobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800">Fitur</a>
                    <a href="#pricing" @click="isMobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800">Harga</a>
                    <a href="#faq" @click="isMobileMenuOpen = false" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800">FAQ</a>
                    <Link :href="route('login')" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:text-teal-600 dark:hover:text-teal-400 hover:bg-gray-50 dark:hover:bg-gray-800 border-t border-gray-100 dark:border-gray-700 pt-3 mt-3">Masuk</Link>
                    <Link :href="route('register')" class="block w-full bg-teal-600 text-white px-3 py-2 rounded-md text-base font-medium text-center hover:bg-teal-700 mt-2">Mulai</Link>
                </div>
            </div>
        </nav>

        <section class="relative pt-32 pb-20 overflow-hidden">
            <div class="hero-gradient absolute inset-0"></div>
            <div class="particles-container" id="particles-js"></div>
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
                <div class="text-center">
                    <h1 class="text-5xl md:text-7xl font-bold text-gray-900 dark:text-white mb-6 animate-on-scroll">
                        Manajemen Inventaris <span class="text-gradient block">Cerdas</span>
                    </h1>
                    <p class="text-xl md:text-2xl text-gray-600 dark:text-gray-400 mb-8 max-w-3xl mx-auto animate-on-scroll" style="animation-delay: 0.2s;">
                        Sederhanakan operasi gudang Anda dengan manajemen stok cerdas, pelacakan real-time, dan pelaporan otomatis.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16 animate-on-scroll" style="animation-delay: 0.4s;">
                        <button class="bg-teal-600 text-white px-8 py-4 rounded-full text-lg font-semibold hover:bg-teal-700 transition-all transform hover:scale-105 shadow-lg">Mulai Uji Coba Gratis</button>
                        <button class="border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 px-8 py-4 rounded-full text-lg font-semibold hover:border-teal-600 hover:text-teal-600 dark:hover:border-teal-500 dark:hover:text-teal-500 transition-all">Tonton Demo</button>
                    </div>
                </div>
                <div class="relative max-w-5xl mx-auto animate-on-scroll" style="animation-delay: 0.6s;">
                    <div class="bg-white dark:bg-gray-800/50 rounded-2xl shadow-2xl p-6 animate-float">
                        <div class="bg-gray-100 dark:bg-gray-800 rounded-xl p-4 sm:p-8 space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex space-x-2">
                                    <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                    <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                </div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">Dasbor</div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm"><div class="text-2xl font-bold text-teal-600 dark:text-teal-400">1,234</div><div class="text-sm text-gray-600 dark:text-gray-400">Total Produk</div></div>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm"><div class="text-2xl font-bold text-green-600 dark:text-green-400">Rp712Jt</div><div class="text-sm text-gray-600 dark:text-gray-400">Nilai Inventaris</div></div>
                                <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm"><div class="text-2xl font-bold text-cyan-600 dark:text-cyan-400">89%</div><div class="text-sm text-gray-600 dark:text-gray-400">Efisiensi Stok</div></div>
                            </div>
                            <div class="bg-white dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                <div class="h-24 bg-gradient-to-r from-teal-500 to-cyan-600 rounded opacity-20"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="features" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 animate-on-scroll">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Fitur Andal untuk <span class="text-gradient">Bisnis Modern</span></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk mengelola inventaris secara efisien dan mengembangkan bisnis Anda</p>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="text-center p-8 rounded-2xl bg-teal-50 dark:bg-gray-900 card-hover animate-on-scroll">
                        <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6"><svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg></div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Pelacakan Inventaris</h3>
                        <p class="text-gray-600 dark:text-gray-400">Pemantauan tingkat stok secara real-time dengan peringatan otomatis untuk persediaan rendah dan titik pemesanan ulang.</p>
                    </div>
                    <div class="text-center p-8 rounded-2xl bg-teal-50 dark:bg-gray-900 card-hover animate-on-scroll" style="animation-delay: 0.1s;">
                        <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6"><svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg></div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Analitik & Laporan</h3>
                        <p class="text-gray-600 dark:text-gray-400">Pelaporan komprehensif dengan wawasan tentang pergerakan stok, tren penjualan, dan kinerja inventaris.</p>
                    </div>
                    <div class="text-center p-8 rounded-2xl bg-teal-50 dark:bg-gray-900 card-hover animate-on-scroll" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 feature-icon rounded-2xl flex items-center justify-center mx-auto mb-6"><svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg></div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Manajemen Tim</h3>
                        <p class="text-gray-600 dark:text-gray-400">Kontrol akses berbasis peran untuk admin, manajer gudang, dan staf dengan izin yang dapat disesuaikan.</p>
                    </div>
                </div>
            </div>
        </section>

          <section id="how-it-works" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16 animate-on-scroll">
                <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    Bagaimana Stockify
                    <span class="text-gradient">Menyederhanakan Alur Kerja Anda</span>
                </h2>
                <p class="text-xl text-gray-600 max-w-2xl mx-auto">
                    Tinjauan komprehensif tentang fungsionalitas inti Stockify
                </p>
            </div>

            <div class="space-y-16">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div class="order-2 md:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Manajemen Produk
                        </h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Sederhanakan entri dan organisasi data produk. Admin dapat mendefinisikan
                            kategori dan atribut produk, sementara Manajer Gudang menambahkan produk baru dengan informasi
                            terperinci termasuk gambar. Semua data disimpan dengan aman dan mudah diakses.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Tambahkan kategori produk baru (mis., Elektronik, Pakaian,
                                Makanan).</li>
                            <li>Definisikan atribut produk seperti ukuran, warna, dan berat.</li>
                            <li>Catat detail produk: nama, deskripsi, harga beli,
                                harga jual, stok awal, dan gambar.</li>
                        </ul>
                    </div>
                    <div class="order-1 md:order-2">
                        <img src="https://via.placeholder.com/600x400/add8e6/808080?text=Manajemen+Produk" alt="Manajemen Produk" class="rounded-lg shadow-xl">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div>
                        <img src="https://via.placeholder.com/600x400/90ee90/808080?text=Manajemen+Stok" alt="Manajemen Stok" class="rounded-lg shadow-xl">
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Manajemen Stok</h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Lacak setiap item dengan pembaruan real-time. Staf Gudang
                            menangani transaksi masuk dan keluar, sementara Manajer Gudang memantau tingkat stok,
                            peringatan stok minimum, dan melakukan stock opname untuk akurasi.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Catat transaksi masuk dan keluar.</li>
                            <li>Pantau tingkat stok real-time, termasuk stok minimum dan
                                stok yang tersedia.</li>
                            <li>Lakukan stock opname secara berkala untuk rekonsiliasi inventaris.
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center animate-on-scroll">
                    <div class="order-2 md:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-4">Manajemen Pemasok
                        </h3>
                        <p class="text-lg text-gray-600 mb-4">
                            Pelihara basis data pemasok Anda yang komprehensif. Admin dapat
                            mengelola data pemasok, sementara Manajer Gudang dengan mudah memilih pemasok saat mencatat
                            barang masuk.
                        </p>
                        <ul class="list-disc list-inside text-gray-700 space-y-2">
                            <li>Tambah, perbarui, dan hapus informasi pemasok (nama, alamat,
                                kontak).</li>
                            <li>Kaitkan produk dengan pemasok masing-masing untuk
                                pelacakan yang lebih baik.</li>
                        </ul>
                    </div>
                    <div class="order-1 md:order-2">
                        <img src="https://via.placeholder.com/600x400/ffb6c1/808080?text=Manajemen+Pemasok" alt="Manajemen Pemasok" class="rounded-lg shadow-xl">
                    </div>
                </div>
            </div>
        </div>
    </section>

        <section id="pricing" class="py-20 bg-teal-50 dark:bg-gray-900">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 animate-on-scroll">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Harga Sederhana, <span class="text-gradient">Transparan</span></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Pilih paket yang sempurna untuk kebutuhan bisnis Anda</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-stretch">
                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg card-hover animate-on-scroll flex flex-col">
                        <div class="text-center flex-grow">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Dasar</h3>
                            <div class="text-4xl font-bold text-teal-600 dark:text-teal-400 mb-2">Rp150rb</div>
                            <div class="text-gray-600 dark:text-gray-400 mb-6">per bulan</div>
                            <ul class="text-left space-y-3 mb-8 text-gray-600 dark:text-gray-400">
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Hingga 100 produk</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Laporan dasar</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>Dukungan email</li>
                            </ul>
                        </div>
                        <button class="w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-full font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors mt-auto">Pilih Dasar</button>
                    </div>

                    <div class="bg-teal-600 text-white p-8 rounded-2xl shadow-lg card-hover relative animate-on-scroll flex flex-col" style="animation-delay: 0.1s;">
                        <div class="absolute -top-4 left-1/2 transform -translate-x-1/2">
                            <span class="bg-yellow-400 text-gray-900 px-4 py-1 rounded-full text-sm font-semibold">Paling Populer</span>
                        </div>
                        <div class="text-center flex-grow">
                            <h3 class="text-2xl font-bold mb-4">Pro</h3>
                            <div class="text-4xl font-bold mb-2">Rp300rb</div>
                            <div class="text-teal-200 mb-6">per bulan</div>
                            <ul class="text-left space-y-3 mb-8">
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Hingga 1.000 produk</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Analitik lanjutan</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Kolaborasi tim</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-300 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Dukungan prioritas</li>
                            </ul>
                        </div>
                        <button class="w-full bg-white text-teal-600 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors mt-auto">Pilih Pro</button>
                    </div>

                    <div class="bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-lg card-hover animate-on-scroll flex flex-col" style="animation-delay: 0.2s;">
                        <div class="text-center flex-grow">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Enterprise</h3>
                            <div class="text-4xl font-bold text-teal-600 dark:text-teal-400 mb-2">Rp450rb</div>
                            <div class="text-gray-600 dark:text-gray-400 mb-6">per bulan</div>
                            <ul class="text-left space-y-3 mb-8 text-gray-600 dark:text-gray-400">
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Produk tak terbatas</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Integrasi kustom</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Keamanan tingkat lanjut</li>
                                <li class="flex items-center"><svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg> Dukungan telepon 24/7</li>
                            </ul>
                        </div>
                        <button class="w-full bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white py-3 rounded-full font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors mt-auto">Pilih Enterprise</button>
                    </div>
                </div>
            </div>
        </section>

        <section id="faq" class="py-20 bg-white dark:bg-gray-800">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16 animate-on-scroll">
                    <h2 class="text-4xl md:text-5xl font-bold text-gray-900 dark:text-white mb-4">Pertanyaan yang Sering <span class="text-gradient">Diajukan</span></h2>
                    <p class="text-xl text-gray-600 dark:text-gray-400 max-w-2xl mx-auto">Temukan jawaban cepat untuk pertanyaan paling umum tentang Stockify.</p>
                </div>

                <div class="space-y-4">
                    <div v-for="faq in faqs" :key="faq.id" class="border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm animate-on-scroll bg-white dark:bg-gray-800">
                        <button @click="toggleFaq(faq.id)" class="flex justify-between items-center w-full p-6 text-left font-semibold text-lg text-gray-800 dark:text-gray-200 focus:outline-none">
                            <span>{{ faq.question }}</span>
                            <svg :class="{ 'rotate-180': faq.isOpen }" class="w-5 h-5 text-gray-500 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        <transition enter-active-class="transition ease-out duration-300" enter-from-class="opacity-0 transform -translate-y-2" enter-to-class="opacity-100 transform translate-y-0" leave-active-class="transition ease-in duration-200" leave-from-class="opacity-100 transform translate-y-0" leave-to-class="opacity-0 transform -translate-y-2">
                            <div v-show="faq.isOpen" class="px-6 pb-6 text-gray-600 dark:text-gray-400">
                                {{ faq.answer }}
                            </div>
                        </transition>
                    </div>
                </div>
            </div>
        </section>

        <section id="contact" class="py-20 bg-gradient-to-r from-teal-600 to-cyan-700 animate-on-scroll">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">Siap Mengubah Inventaris Anda?</h2>
                <p class="text-xl text-teal-100 mb-8 max-w-2xl mx-auto">Bergabunglah dengan ribuan bisnis yang mempercayai Stockify untuk kebutuhan manajemen inventaris mereka.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-white text-teal-600 px-8 py-4 rounded-full text-lg font-semibold hover:bg-gray-100 transition-all transform hover:scale-105 shadow-lg">Mulai Sekarang</button>
                    <button class="border-2 border-teal-200 text-white px-8 py-4 rounded-full text-lg font-semibold hover:border-white hover:bg-white/10 transition-all">Hubungi Penjualan</button>
                </div>
            </div>
        </section>

        <footer class="bg-gray-900 text-gray-400 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex items-center justify-center mb-6">
                    <div class="w-10 h-10 bg-gradient-to-r from-teal-500 to-cyan-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-xl">S</span>
                    </div>
                    <div class="ml-3">
                        <span class="text-2xl font-bold text-white">Stockify</span>
                    </div>
                </div>
                <p class="text-gray-400 mb-6">
                    &copy; 2025 Stockify. Hak cipta dilindungi undang-undang.
                </p>
                <div class="flex justify-center space-x-6 text-gray-400">
                    <a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-white transition-colors">Ketentuan Layanan</a>
                    <a href="#" class="hover:text-white transition-colors">Peta Situs</a>
                </div>
            </div>
        </footer>
    </div>
</template>