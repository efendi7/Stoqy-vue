<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\ProductAttributeController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\StockTransactionController;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('users', UserController::class);
Route::resource('products', ProductController::class);
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::resource('categories', CategoryController::class);
Route::resource('suppliers', SupplierController::class);
Route::resource('product_attributes', ProductAttributeController::class);
Route::post('/contact-submit', [ContactController::class, 'submit'])->name('contact.submit');
Route::resource('stock_transactions', StockTransactionController::class);
