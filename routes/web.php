<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BukuController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/counts', [BukuController::class, 'getCounts'])->middleware(['auth', 'verified'])->name('dashboard.counts');


Route::prefix('kategori')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [KategoriController::class, 'index'])->name('kategori.index'); 
    Route::get('/data', [KategoriController::class, 'getData'])->name('kategori.data'); 
    Route::post('/', [KategoriController::class, 'store'])->name('kategori.store'); 
    Route::get('{id}/edit', [KategoriController::class, 'edit'])->name('kategori.edit'); 
    Route::post('{id}', [KategoriController::class, 'update'])->name('kategori.update'); 
    Route::delete('{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy'); 
});

Route::prefix('buku')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [BukuController::class, 'index'])->name('buku.index'); 
    Route::get('/data', [BukuController::class, 'getData'])->name('buku.data'); 
    Route::post('/', [BukuController::class, 'store'])->name('buku.store'); 
    Route::get('{id}/edit', [BukuController::class, 'edit'])->name('buku.edit'); 
    Route::post('{id}', [BukuController::class, 'update'])->name('buku.update'); 
    Route::delete('{id}', [BukuController::class, 'destroy'])->name('buku.destroy'); 
});

require __DIR__ . '/auth.php';
