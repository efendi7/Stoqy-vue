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

// Rute laporan
Route::prefix('laporan')->middleware('auth')->group(function () {
    Route::get('/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('/aktivitas', [LaporanController::class, 'aktivitas'])->name('laporan.aktivitas');

    // Tambahkan filter berdasarkan kategori dan periode
    Route::get('/stok/filter', [LaporanController::class, 'stokFilter'])->name('laporan.stok.filter');
    Route::get('/transaksi/filter', [LaporanController::class, 'transaksiFilter'])->name('laporan.transaksi.filter');

    // Ekspor laporan ke Excel atau PDF
    Route::get('/stok/export', [LaporanController::class, 'exportStok'])->name('laporan.stok.export');
    Route::get('/transaksi/export', [LaporanController::class, 'exportTransaksi'])->name('laporan.transaksi.export');
});


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
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');

    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    Route::resource('stock_transactions', StockTransactionController::class);
    Route::patch('/stock_transactions/{id}/update_status', [StockTransactionController::class, 'updateStatus'])->name('stock_transactions.update_status');
Route::post('/stock_transactions/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('stock_transactions.confirm');

Route::get('/transactions/outgoing/{transaction}', [StockTransactionController::class, 'showOutgoing'])->name('transactions.outgoing.show');
Route::get('/staff/transactions/outgoing/{id}', [TransactionController::class, 'show'])
    ->name('staff.transactions.outgoing.show');


Route::get('/transactions/incoming/{transaction}', [StockTransactionController::class, 'show'])->name('transactions.incoming.show');

Route::get('/stock-transactions/pending', [StockTransactionController::class, 'pending'])->name('stock-transactions.pending');
Route::get('/stock-transactions/confirmed', [StockTransactionController::class, 'confirmed'])->name('stock-transactions.confirmed');


Route::post('/stock_transactions/{id}/add_note', [StockTransactionController::class, 'addNote'])->name('stock_transactions.add_note');


  
    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    Route::put('/stock_opname/update/{product_id}', [StockOpnameController::class, 'updateStock'])
    ->name('stock_opname.updateStock');


    Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

    // Endpoint tambahan untuk fitur khusus
    Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('stock_transactions.set_minimum_stock');
    Route::put('/stock_opname/update-to-actual/{id}', [StockOpnameController::class, 'updateStockToActual'])
    ->name('stock_opname.update_to_actual');
    Route::get('/stock_opname_manual', [StockTransactionController::class, 'stockOpname'])->name('stock_transactions.opname');
    Route::post('/stock-opname', [StockOpnameController::class, 'store'])->name('stock_opname.store');
    Route::put('/stock-opname/{id}', [StockOpnameController::class, 'update'])->name('stock_opname.update');



    // User activity routes
    
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs')->middleware('role:admin');
    Route::delete('/laporan/aktivitas/{id}', [LaporanController::class, 'destroy'])->name('laporan.aktivitas.hapus');

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
