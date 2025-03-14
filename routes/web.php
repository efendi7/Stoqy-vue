<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    Auth\LoginController,
    Auth\RegisterController,
    CategoryController,
    ContactController,
    DashboardController,
    LaporanController,
    ProductAttributeController,
    ProductController,
    ProductImportExportController,
    ProfileController,
    SettingController,
    StockOpnameController,
    StockTransactionController,
    SupplierController,
    UserController
};

// Rute untuk pengguna tamu (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Rute utama
Route::get('/', fn() => view('welcome'));

// Rute untuk profil pengguna
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// Rute laporan
Route::prefix('laporan')->middleware('auth')->group(function () {
    Route::get('/stok', [LaporanController::class, 'stok'])->name('laporan.stok');
    Route::get('/transaksi', [LaporanController::class, 'transaksi'])->name('laporan.transaksi');
    Route::get('/aktivitas', [LaporanController::class, 'aktivitas'])->name('laporan.aktivitas');
    Route::get('/stok/filter', [LaporanController::class, 'stokFilter'])->name('laporan.stok.filter');
    Route::get('/transaksi/filter', [LaporanController::class, 'transaksiFilter'])->name('laporan.transaksi.filter');
    Route::get('/stok/export', [LaporanController::class, 'exportStok'])->name('laporan.stok.export');
    Route::get('/transaksi/export', [LaporanController::class, 'exportTransaksi'])->name('laporan.transaksi.export');
    Route::delete('/aktivitas/{id}', [LaporanController::class, 'destroy'])->name('laporan.aktivitas.hapus');
});

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rute setelah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs')->middleware('role:admin');

    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    Route::resource('products', ProductController::class);

    Route::prefix('products')->group(function () {
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::get('/import-export', [ProductImportExportController::class, 'index'])->name('products.import-export.index');
        Route::get('/export', [ProductImportExportController::class, 'export'])->name('products.export');
        Route::post('/import', [ProductImportExportController::class, 'import'])->name('products.import');
        Route::get('/export-template', [ProductImportExportController::class, 'exportTemplate'])->name('products.export-template');
        Route::get('/{product}', [ProductController::class, 'show'])->where('product', '[0-9]+')->name('products.show');
    });

    Route::resource('stock_transactions', StockTransactionController::class);
    Route::prefix('stock_transactions')->group(function () {
        Route::patch('/{id}/update_status', [StockTransactionController::class, 'updateStatus'])->name('stock_transactions.update_status');
        Route::post('/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('stock_transactions.confirm');
        Route::get('/pending', [StockTransactionController::class, 'pending'])->name('stock_transactions.pending');
        Route::get('/confirmed', [StockTransactionController::class, 'confirmed'])->name('stock_transactions.confirmed');
        Route::post('/{id}/add_note', [StockTransactionController::class, 'addNote'])->name('stock_transactions.add_note');
        Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('stock_transactions.set_minimum_stock');
    });

    Route::get('/transactions/outgoing/{transaction}', [StockTransactionController::class, 'showOutgoing'])->name('transactions.outgoing.show');
    Route::get('/transactions/incoming/{transaction}', [StockTransactionController::class, 'show'])->name('transactions.incoming.show');
    Route::get('/staff/transactions/outgoing/{id}', [StockTransactionController::class, 'show'])->name('staff.transactions.outgoing.show');

    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    Route::put('/stock_opname/updateStock/{id}', [StockOpnameController::class, 'updateStock'])->name('stock_opname.updateStock');

    Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});
