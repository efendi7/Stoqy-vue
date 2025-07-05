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
    AdminController
};

// --- Rute Autentikasi & Registrasi ---
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// --- Rute Pengajuan & Persetujuan Role (Admin) ---
Route::get('/request-role', [UserController::class, 'showRequestRolePage'])->name('request.role.page');
Route::post('/request-role', [UserController::class, 'requestRole'])->name('request.role');

// SEMENTARA: Ganti role:admin dengan pengecekan manual
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/role-requests', [AdminController::class, 'roleRequests'])->name('role-requests');
    Route::post('/approve-role/{id}', [AdminController::class, 'approveRole'])->name('approve.role');
    Route::post('/reject-role/{id}', [AdminController::class, 'rejectRole'])->name('reject.role');
});

// --- Rute Umum ---
Route::get('/', fn() => view('welcome'));

// --- Rute Yang Membutuhkan Autentikasi ---
Route::middleware(['auth'])->group(function () {
    Route::get('/api/categories', [ProductController::class, 'getCategories'])->name('api.categories');
        Route::get('/api/suppliers', [ProductController::class, 'getSuppliers'])->name('api.suppliers');
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil Pengguna
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Manajemen Pengguna
    Route::resource('users', UserController::class);
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    
    // SEMENTARA: Ganti role:admin dengan auth saja
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs');

    // Manajemen Kategori
    Route::resource('categories', CategoryController::class);

    // Manajemen Supplier
    Route::resource('suppliers', SupplierController::class);

    // Manajemen Atribut Produk
    Route::resource('product_attributes', ProductAttributeController::class);

   Route::resource('products', ProductController::class)->except(['create']); // Keep except('create') if you don't need a dedicated create page

    Route::prefix('products')->name('products.')->group(function () {
        // These specific routes are implicitly covered by Route::resource above now,
        // but it's okay to explicitly define them if you prefer.
        // If you keep Route::resource('products', ProductController::class),
        // then products.index, products.store, products.show will be created automatically.
        // If you used ->except(['index', 'store', 'show']), then the explicit ones below are needed.
        // Given your current setup:
        // 'index', 'store', 'show' are defined *outside* the `except` for resource, so they're fine.
        // Route::get('/', [ProductController::class, 'index'])->name('index'); // This is already covered by resource
        // Route::post('/', [ProductController::class, 'store'])->name('store'); // This is already covered by resource
        // Route::get('/{product}', [ProductController::class, 'show'])->where('product', '[0-9]+')->name('show'); // This is already covered by resource

        Route::get('/import-export', [ProductImportExportController::class, 'index'])->name('import-export.index');
        Route::get('/export', [ProductImportExportController::class, 'export'])->name('export');
        Route::post('/import', [ProductImportExportController::class, 'import'])->name('import');
        Route::get('/export-template', [ProductImportExportController::class, 'exportTemplate'])->name('export-template');
        
    });


    // Manajemen Transaksi Stok
    Route::resource('stock_transactions', StockTransactionController::class)->except(['show']);

    Route::prefix('stock-transactions')->name('stock_transactions.')->group(function () {
        Route::get('/pending', [StockTransactionController::class, 'pendingIndex'])->name('pending');
        Route::get('/confirmed', [StockTransactionController::class, 'confirmedIndex'])->name('confirmed');
        Route::get('/incoming/{transaction}', [StockTransactionController::class, 'showIncomingDetail'])->name('incoming.detail');
        Route::get('/outgoing/{transaction}', [StockTransactionController::class, 'showOutgoingDetail'])->name('outgoing.detail');
        Route::get('/incoming', [StockTransactionController::class, 'incomingIndex'])->name('incoming.index');
        Route::get('/outgoing', [StockTransactionController::class, 'outgoingIndex'])->name('outgoing.index');
        Route::post('/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('confirm');
        Route::post('/{id}/add_note', [StockTransactionController::class, 'addNote'])->name('add_note');
        Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('set_minimum_stock');
        Route::post('/{id}/update_status', [StockTransactionController::class, 'updateStatus'])->name('update_status');
    });

    // Laporan Stok
    Route::prefix('laporan/stok')->name('laporan.stok.')->group(function () {
        Route::get('/', [StockReportController::class, 'index'])->name('index');
        Route::get('/filter', [StockReportController::class, 'filter'])->name('filter');
        Route::get('/export', [StockReportController::class, 'export'])->name('export');
    });

    // Laporan Aktivitas
    Route::prefix('laporan/aktivitas')->name('laporan.aktivitas.')->group(function () {
        Route::get('/', [ActivityLogController::class, 'index'])->name('index');
        Route::delete('/{id}', [ActivityLogController::class, 'destroy'])->name('hapus');
        Route::delete('/delete-all', [ActivityLogController::class, 'deleteAllLogs'])->name('delete-all');
    });

    // Stock Opname
    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    Route::put('/stock_opname/updateStock/{id}', [StockOpnameController::class, 'updateStock'])->name('stock_opname.updateStock');

    // Pengaturan Aplikasi (SEMENTARA: tanpa role middleware)
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});