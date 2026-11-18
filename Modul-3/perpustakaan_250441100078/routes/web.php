<?php

use App\Http\Controllers\BukuController;
use Illuminate\Support\Facades\Route;

// 1. Halaman Beranda (Named Route: home)
Route::view('/', 'beranda')->name('home');

// 2. Halaman Daftar Buku (Named Route: buku.index)
Route::get('/buku', [BukuController::class, 'index'])->name('buku.index');

// 3. Halaman Detail Buku dengan Parameter ID (Named Route: buku.show)
Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');