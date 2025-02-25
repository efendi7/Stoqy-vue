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

// Halaman utama
Route::get('/', function () {
    return view('welcome');
});

// Rute untuk login dan registrasi
Route::middleware('guest')->group(function() {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Rute logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Rute yang hanya bisa diakses setelah login (tanpa verifikasi email)
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('product_attributes', ProductAttributeController::class);
    Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');
    Route::resource('stock_transactions', StockTransactionController::class);
    Route::get('/stock_opname', [StockTransactionController::class, 'stockOpname'])->name('stock_transactions.opname');
    Route::post('/set_minimum_stock', [StockTransactionController::class, 'setMinimumStock'])->name('stock_transactions.set_minimum_stock');
    
    // User activity routes
    Route::get('/users/{user}/activity', [UserController::class, 'activity'])->name('users.activity');
    Route::get('/activity-logs', [UserController::class, 'allActivities'])->name('activity.logs')->middleware('role:admin');
});
