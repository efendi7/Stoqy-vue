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
    UserController
};

// Rute untuk pengguna tamu (belum login)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Halaman untuk user yang ingin mengajukan role
Route::get('/request-role', [UserController::class, 'showRequestRolePage'])->name('request.role.page');
Route::post('/request-role', [UserController::class, 'requestRole'])->name('request.role');

// Halaman untuk admin melihat dan menyetujui pengajuan role
use App\Http\Controllers\AdminController;

Route::get('/admin/role-requests', [AdminController::class, 'roleRequests'])->name('admin.role-requests');
Route::post('/admin/approve-role/{id}', [AdminController::class, 'approveRole'])->name('approve.role');
Route::post('/admin/reject-role/{id}', [AdminController::class, 'rejectRole'])->name('reject.role');




// Rute utama
Route::get('/', fn() => view('welcome'));

// Rute untuk profil pengguna
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

//rute laporan stok
Route::prefix('laporan/stok')->middleware('auth')->group(function () {
    Route::get('/', [StockReportController::class, 'index'])->name('laporan.stok'); // Tampilkan laporan stok
    Route::get('/filter', [StockReportController::class, 'filter'])->name('laporan.stok.filter'); // Filter laporan stok
    Route::get('/export', [StockReportController::class, 'export'])->name('laporan.stok.export'); // Ekspor laporan stok
});

//rute aktivitas log
Route::prefix('laporan/aktivitas')->middleware('auth')->group(function () {
    Route::get('/', [ActivityLogController::class, 'index'])->name('laporan.aktivitas'); // Lihat laporan aktivitas
    Route::delete('/{id}', [ActivityLogController::class, 'destroy'])->name('laporan.aktivitas.hapus'); // Hapus aktivitas
    Route::delete('/activities/delete-all', [ActivityLogController::class, 'deleteAllLogs'])->name('activities.delete-all');
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
        Route::get('/{id}/update_status', [StockTransactionController::class, 'updateStatus'])->name('stock_transactions.update_status');
        Route::post('/{id}/confirm', [StockTransactionController::class, 'confirm'])->name('stock_transactions.confirm');
        Route::post('/{id}/add_note', [StockTransactionController::class, 'addNote'])->name('stock_transactions.add_note');
        Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('stock_transactions.set_minimum_stock');
    });

    Route::get('/pending', [StockTransactionController::class, 'pending'])->name('stock_transactions.pending');
    Route::get('/confirmed', [StockTransactionController::class, 'confirmed'])->name('stock_transactions.confirmed');

    Route::get('/transactions/outgoing/{transaction}', [StockTransactionController::class, 'showOutgoing'])->name('transactions.outgoing.show');
    Route::get('/transactions/incoming/{transaction}', [StockTransactionController::class, 'show'])->name('transactions.incoming.show');
    Route::get('/staff/transactions/outgoing/{id}', [StockTransactionController::class, 'show'])->name('staff.transactions.outgoing.show');

    Route::resource('stock_opname', StockOpnameController::class)->only(['index', 'store']);
    Route::put('/stock_opname/updateStock/{id}', [StockOpnameController::class, 'updateStock'])->name('stock_opname.updateStock');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingController::class, 'update'])->name('settings.update');
});
