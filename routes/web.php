<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\StockOpnameController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ProductImportExportController;
use App\Http\Controllers\LaporanController;


// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

Route::get('/laporan/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
Route::get('/laporan/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
Route::get('/laporan/aktivitas', [LaporanController::class, 'aktivitas'])->name('laporan.aktivitas');

Route::post('/stock-transactions/{id}/update-status', [StockTransactionController::class, 'updateStatus'])->name('stock_transactions.update-status');


Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Logout route
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang hanya bisa diakses setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    Route::resource('stock_transactions', StockTransactionController::class);
    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);

    Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

    // Endpoint tambahan untuk fitur khusus
    Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('stock_transactions.set_minimum_stock');
    Route::get('/stock_opname_manual', [StockTransactionController::class, 'stockOpname'])->name('stock_transactions.opname');
    Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock_opname.store');
    Route::put('/stock-opname/{id}', [StockOpnameController::class, 'update'])->name('stock_opname.update');



    // User activity routes
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs')->middleware('role:admin');

    // ✅ Tambahkan rute untuk pengaturan aplikasi
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // Rute CRUD produk
    Route::resource('products', ProductController::class);
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');



   
    // Rute import & export produk
    Route::get('/products/import-export', [ProductImportExportController::class, 'index'])->name('products.import-export.index');
    Route::get('/products/export', [ProductImportExportController::class, 'export'])->name('products.export');
    Route::post('/products/import', [ProductImportExportController::class, 'import'])->name('products.import');
    Route::get('/products/export-template', [ProductImportExportController::class, 'exportTemplate'])
    ->name('products.export-template');

    // Rute khusus untuk menampilkan satu produk berdasarkan ID numerik
    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->where('product', '[0-9]+')
        ->name('products.show');
});
