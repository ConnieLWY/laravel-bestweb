<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryApiController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/products',  [ProductApiController::class, 'index'])->middleware('auth:sanctum');
Route::put('/products/{id}', [ProductApiController::class, 'update'])->middleware('auth:sanctum');
Route::get('/products/{id}', [ProductApiController::class, 'show'])->middleware('auth:sanctum');
Route::post('/products', [ProductApiController::class, 'store'])->middleware('auth:sanctum');
Route::post('/products/bulk-delete', [ProductApiController::class, 'bulkDelete'])->middleware('auth:sanctum');
Route::delete('/products/{id}', [ProductApiController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/export/products', [ProductApiController::class, 'export']);

Route::get('/categories', [CategoryApiController::class, 'index'])->middleware('auth:sanctum');
Route::post('/categories', [CategoryApiController::class, 'store'])->middleware('auth:sanctum');
Route::get('/categories/{id}', [CategoryApiController::class, 'show'])->middleware('auth:sanctum');
Route::put('/categories/{id}', [CategoryApiController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/categories/{id}', [CategoryApiController::class, 'destroy'])->middleware('auth:sanctum');
Route::post('/categories/bulk-delete', [CategoryApiController::class, 'bulkDelete'])->middleware('auth:sanctum');