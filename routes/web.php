<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

Route::get('/', function () {
    return view('welcome');
});

// Route form CRUD dinamis
Route::get('/produk/buat-dinamis', [ProductController::class, 'createDynamic'])->name('products.create.dynamic');
Route::post('/produk/simpan-dinamis', [ProductController::class, 'storeDynamic'])->name('products.store.dynamic');