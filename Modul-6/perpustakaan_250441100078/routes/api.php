<?php

use App\Http\Controllers\Api\BukuController;
use Illuminate\Support\Facades\Route;

// Proteksi endpoint API dengan session web & auth
Route::middleware(['web', 'auth'])->group(function () {
    
    // Hanya pengguna yang punya permission 'lihat daftar buku' (Admin, Petugas, Siswa)
    Route::get('/buku', [BukuController::class, 'index'])->middleware('can:lihat daftar buku');

    // Hanya pengguna yang punya permission 'kelola data buku' (Admin & Petugas)
    Route::post('/buku', [BukuController::class, 'store'])->middleware('can:kelola data buku');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->middleware('can:kelola data buku');
    Route::delete('/buku/{id}', [BukuController::class, 'destroy'])->middleware('can:kelola data buku');

});