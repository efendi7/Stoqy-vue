<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
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
    AdminController
};
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini Anda dapat mendaftarkan rute web untuk aplikasi Anda. Rute-rute
| ini dimuat oleh RouteServiceProvider dalam sebuah grup yang
| berisi grup middleware "web".
|
*/

// --- Rute Halaman Depan ---
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => app()->version(),
        'phpVersion' => PHP_VERSION,
    ]);
});

// --- Rute yang Membutuhkan Autentikasi ---
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
 
    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update'); // Menggunakan PATCH untuk update

    // --- MANAJEMEN ---

    // Manajemen Pengguna
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::resource('users', UserController::class);

    // Manajemen Produk & Atributnya
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    
    Route::prefix('products')->name('products.')->controller(ProductController::class)->group(function() {
        Route::get('/{product}/detail', 'showDetail')->name('showDetail');
    });
    Route::resource('products', ProductController::class);

    Route::prefix('products')->name('products.')->controller(ProductImportExportController::class)->group(function() {
        Route::get('/import-export', 'index')->name('import-export.index');
        Route::get('/export', 'export')->name('export');
        Route::post('/import', 'import')->name('import');
        Route::get('/export-template', 'exportTemplate')->name('export-template');
    });

    // --- GUDANG (WAREHOUSE) ---

    // Transaksi Stok
    // KONSOLIDASI: Semua view (pending, confirmed, dll) kini ditangani oleh method 'index' di controller.
    Route::prefix('stock-transactions')->name('stock_transactions.')->controller(StockTransactionController::class)->group(function () {
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/confirmed', 'confirmed')->name('confirmed');
        Route::get('/incoming', 'index')->name('incoming.index');
        Route::get('/outgoing', 'index')->name('outgoing.index');
        
        // Rute untuk aksi spesifik
        Route::patch('/{id}/confirm', 'confirm')->name('confirm');
        Route::post('/{id}/add_note', 'addNote')->name('add_note');
        Route::patch('/{id}/update_status', 'updateStatus')->name('update_status'); // Menggunakan PATCH untuk update parsial
        Route::post('/set_minimum_stock', 'setMinimumStock')->name('set_minimum_stock');
        
        // Rute untuk detail (jika masih diperlukan)
        Route::get('/incoming/{transaction}', 'showIncomingDetail')->name('incoming.detail');
        Route::get('/outgoing/{transaction}', 'showOutgoingDetail')->name('outgoing.detail');
    });
    Route::resource('stock_transactions', StockTransactionController::class);

    // Stock Opname
    Route::put('/stock_opname/updateStock/{id}', [StockOpnameController::class, 'updateStock'])->name('stock_opname.updateStock');
    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    
    // --- LAPORAN & LOG ---

    // Laporan Stok
    Route::prefix('laporan/stok')->name('laporan.stok.')->controller(StockReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/filter', 'filter')->name('filter');
        Route::get('/export', 'export')->name('export');
    });

    // Log Aktivitas
  Route::prefix('laporan/aktivitas')->name('laporan.aktivitas.')->controller(ActivityLogController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::delete('/delete-all', 'deleteAllLogs')->name('delete-all'); // Pindahkan ke atas
    Route::delete('/{id}', 'destroy')->name('hapus');
});
    
    // --- PENGATURAN & ADMIN ---

    // Pengaturan Aplikasi
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // Rute Khusus Admin
    Route::middleware('can:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/role-requests', [AdminController::class, 'roleRequests'])->name('role-requests');
        Route::post('/approve-role/{id}', [AdminController::class, 'approveRole'])->name('approve.role');
        Route::post('/reject-role/{id}', [AdminController::class, 'rejectRole'])->name('reject.role');
    });
});

// --- Rute Otentikasi Bawaan Breeze ---
require __DIR__.'/auth.php';