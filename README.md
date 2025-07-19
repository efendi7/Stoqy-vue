<p align="center">
  <img src="https://laravel.com/img/logomark.min.svg" alt="Laravel" height="60"/>
  <img src="https://upload.wikimedia.org/wikipedia/commons/9/95/Vue.js_Logo_2.svg" alt="Vue.js" height="60"/>
  <img src="https://raw.githubusercontent.com/inertiajs/inertia/master/inertia-logo.svg" alt="Inertia.js" height="60"/>
</p>

<h1 align="center">📦 Stoqy</h1>

<p align="center">
  Aplikasi manajemen stok barang berbasis Laravel, Vue 3, dan Inertia.js.
</p>

---

## 📝 Deskripsi

**Stoqy** adalah sistem manajemen stok barang yang dirancang untuk membantu pemilik toko dan pengelola gudang dalam mencatat, mengontrol, dan menganalisis arus barang. Aplikasi ini memiliki sistem peran pengguna (Admin, Manajer Gudang, dan Staff Gudang) serta antarmuka SPA modern.

---

## 🚀 Fitur Unggulan

- ✅ Manajemen Produk, Stok, Supplier
- ✅ Transaksi Stok Masuk & Keluar (Pending/Confirmed)
- ✅ Dashboard Statistik
- ✅ Sistem Role & Otentikasi Pengguna
- ✅ Riwayat Aktivitas (Activity Log)
- ✅ Laporan PDF & Excel
- ✅ Notifikasi Flash Message
- ✅ Mode Gelap (Dark Mode)
- ✅ SPA penuh menggunakan Inertia.js

---

## 🧰 Teknologi yang Digunakan

| Lapisan       | Teknologi                              |
|---------------|-----------------------------------------|
| Backend       | Laravel 11, PHP 8.3                    |
| Frontend      | Vue 3, Inertia.js, Tailwind CSS, Flowbite |
| Build Tool    | Vite                                   |
| Database      | MySQL                                  |
| Arsitektur    | Repository-Service Pattern             |

---

## 🏗️ Struktur Direktori

```bash
stoqy/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Services/
│   ├── Repositories/
├── resources/
│   └── js/
│       ├── Pages/
│       ├── Components/
├── routes/
│   └── web.php
├── database/
│   └── migrations/
├── public/
└── README.md
```

---

## ⚙️ Instalasi

Langkah instalasi lokal:

```bash
git clone https://github.com/username/stoqy.git
cd stoqy

composer install
npm install

cp .env.example .env
php artisan key:generate
php artisan migrate --seed

npm run dev
php artisan serve
```

Buka di browser: [http://localhost:8000](http://localhost:8000)

---

## ✅ Format Commit (Conventional Commit)

Gunakan format berikut saat commit:

- `feat`: Fitur baru
- `fix`: Perbaikan bug
- `refactor`: Refactor kode (tanpa menambah fitur atau memperbaiki bug)
- `docs`: Perubahan dokumentasi
- `style`: Perubahan format, spasi, dll (tanpa memengaruhi kode)
- `test`: Menambahkan atau memperbarui pengujian

Contoh:
```bash
git commit -m "feat: menambahkan fitur transaksi stok masuk"
git commit -m "fix: memperbaiki bug saat update supplier"
```

---

## 🤝 Kontribusi

Kami menyambut kontribusi dari siapa pun.

1. Fork repositori
2. Buat branch baru `git checkout -b fitur-anda`
3. Commit perubahan Anda (`git commit -m 'feat: fitur baru'`)
4. Push ke branch Anda (`git push origin fitur-anda`)
5. Buat Pull Request

---

## 📜 Lisensi

Stoqy dirilis di bawah lisensi MIT. Silakan digunakan, disesuaikan, dan dikembangkan.

---

Terima kasih telah menggunakan **Stoqy**! 💙
