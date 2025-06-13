<?php

use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Products/Products');
})->middleware(['auth', 'verified'])->name('products');

Route::get('/products', function () {
    return Inertia::render('Products/Products');
})->middleware(['auth', 'verified'])->name('products');

Route::get('/categories', function () {
    return Inertia::render('Categories/Categories');
})->middleware(['auth', 'verified'])->name('categories');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
    Route::get('/products', [ProductApiController::class, 'index']);
    Route::put('/products/{id}', [ProductApiController::class, 'update']);
    Route::get('/products/{id}', [ProductApiController::class, 'show']);
    Route::post('/products', [ProductApiController::class, 'store']);
    Route::post('/products/bulk-delete', [ProductApiController::class, 'bulkDelete']);
    Route::delete('/products/{id}', [ProductApiController::class, 'destroy']);
    Route::get('/export/products', [ProductApiController::class, 'export']);
});

Route::middleware(['auth'])->prefix('admin')->group(function () {
Route::get('/categories', [CategoryApiController::class, 'index']);
Route::post('/categories', [CategoryApiController::class, 'store']);
Route::get('/categories/{id}', [CategoryApiController::class, 'show']);
Route::put('/categories/{id}', [CategoryApiController::class, 'update']);
Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy']);
Route::post('/categories/bulk-delete', [CategoryApiController::class, 'bulkDelete']);
});

require __DIR__.'/auth.php';
