<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    Auth\RegisterController,
    DashboardController,
    ProductController,
    CategoryController,
    SupplierController,
    ProductAttributeController,
    ProductImportExportController,
    StockTransactionController,
    StockReportController,
    StockOpnameController,
    ActivityLogController,
    ProfileController,
    SettingController,
    UserController,
    AdminController // Pastikan ini di-import
};

// --- Rute Autentikasi & Registrasi ---
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// --- Rute Pengajuan & Persetujuan Role (Admin) ---
// Halaman untuk user yang ingin mengajukan role
Route::get('/request-role', [UserController::class, 'showRequestRolePage'])->name('request.role.page');
Route::post('/request-role', [UserController::class, 'requestRole'])->name('request.role');

// Halaman untuk admin melihat dan menyetujui pengajuan role
// Pastikan middleware 'role:admin' diterapkan jika hanya admin yang bisa mengakses ini
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/role-requests', [AdminController::class, 'roleRequests'])->name('role-requests');
    Route::post('/approve-role/{id}', [AdminController::class, 'approveRole'])->name('approve.role');
    Route::post('/reject-role/{id}', [AdminController::class, 'rejectRole'])->name('reject.role');
});


// --- Rute Umum (Tidak Terautentikasi) ---
Route::get('/', fn() => view('welcome'));

// --- Rute Yang Membutuhkan Autentikasi ---
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Manajemen Pengguna (Biasanya hanya Admin)
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs')->middleware('role:admin');


    // Manajemen Kategori
    Route::resource('categories', CategoryController::class);

    // Manajemen Supplier
    Route::resource('suppliers', SupplierController::class);

    // Manajemen Atribut Produk
    Route::resource('product_attributes', ProductAttributeController::class);

    // Manajemen Produk
    Route::resource('products', ProductController::class)->except(['store', 'index', 'show']); // Nonaktifkan karena sudah ada di prefix
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('index');
        Route::post('/', [ProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ProductController::class, 'show'])->where('product', '[0-9]+')->name('show'); // Pastikan ini tetap spesifik
        // Rute Impor/Ekspor Produk
        Route::get('/import-export', [ProductImportExportController::class, 'index'])->name('import-export.index');
        Route::get('/export', [ProductImportExportController::class, 'export'])->name('export');
        Route::post('/import', [ProductImportExportController::class, 'import'])->name('import');
        Route::get('/export-template', [ProductImportExportController::class, 'exportTemplate'])->name('export-template');
    });

    // Manajemen Transaksi Stok
    // Perhatikan: Rute resource 'stock_transactions' akan membuat banyak rute secara otomatis.
    // Jika ada rute kustom dengan nama yang sama, bisa terjadi konflik atau penimpaan.
    // Biasanya lebih baik menggunakan `except` pada resource jika Anda mendefinisikan rute CRUD secara manual.
    Route::resource('stock_transactions', StockTransactionController::class)->except(['show']); // Nonaktifkan show dari resource karena sudah ada di bawah

    Route::prefix('stock-transactions')->name('transactions.')->group(function () {
        // Rute untuk status pending dan confirmed (untuk Warehouse Staff/Manager)
        Route::get('/pending', [StockTransactionController::class, 'pendingIndex'])->name('pending'); // Menggunakan nama yang lebih deskriptif
        Route::get('/confirmed', [StockTransactionController::class, 'confirmedIndex'])->name('confirmed'); // Menggunakan nama yang lebih deskriptif

        // Rute untuk detail transaksi individu (untuk Warehouse Manager)
        // Ini adalah rute yang sebelumnya hilang.
        Route::get('/incoming/{transaction}', [StockTransactionController::class, 'showIncomingDetail'])->name('incoming.detail'); // Tambahkan ini
        Route::get('/outgoing/{transaction}', [StockTransactionController::class, 'showOutgoingDetail'])->name('outgoing.detail'); // Tambahkan ini

        // Rute untuk halaman daftar semua transaksi masuk/keluar (untuk Admin/Manager)
        Route::get('/incoming', [StockTransactionController::class, 'incomingIndex'])->name('incoming.index');
        Route::get('/outgoing', [StockTransactionController::class, 'outgoingIndex'])->name('outgoing.index');

        // Rute kustom untuk transaksi stok
        Route::post('/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('confirm'); // Mengkonfirmasi transaksi
        Route::post('/{id}/add_note', [StockTransactionController::class, 'addNote'])->name('add_note'); // Menambah catatan transaksi
        Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('set_minimum_stock'); // Mengatur minimum stok (sepertinya bukan bagian dari transaksi)

        // Perbaikan rute update status (jika ini untuk konfirmasi/penolakan)
        // Ini mungkin duplikasi dari `confirm` jika `update_status` juga untuk itu.
        // Jika `update_status` untuk kasus lain (misal: "Diproses" ke "Selesai"), biarkan.
        // Jika ini untuk persetujuan (Diterima/Ditolak), gunakan `confirm` saja atau sesuaikan logicnya.
        Route::post('/{id}/update_status', [StockTransactionController::class, 'updateStatus'])->name('update_status'); // Ubah dari GET ke POST/PUT jika mengubah data
    });


    // Laporan Stok
    Route::prefix('laporan/stok')->name('laporan.stok.')->group(function () {
        Route::get('/', [StockReportController::class, 'index'])->name('index'); // Tampilkan laporan stok
        Route::get('/filter', [StockReportController::class, 'filter'])->name('filter'); // Filter laporan stok
        Route::get('/export', [StockReportController::class, 'export'])->name('export'); // Ekspor laporan stok
    });

    // Laporan Aktivitas
    Route::prefix('laporan/aktivitas')->name('laporan.aktivitas.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index'); // Lihat laporan aktivitas
        Route::delete('/{id}', [ActivityLogController::class, 'destroy'])->name('hapus'); // Hapus aktivitas
        Route::delete('/delete-all', [ActivityLogController::class, 'deleteAllLogs'])->name('delete-all'); // Hapus semua aktivitas
    });

    // Stock Opname
    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    Route::put('/stock_opname/updateStock/{id}', [StockOpnameController::class, 'updateStock'])->name('stock_opname.updateStock');

    // Pengaturan Aplikasi (Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    });

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});