<?php


use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BukuController;

// Soal 1: Route API Resource
Route::apiResource('buku', BukuController::class);

// Soal 7: Route Endpoint Rekap
Route::get('rekap/buku-per-kategori', [BukuController::class, 'rekapBukuPerKategori']);
Route::get('rekap/peminjaman-per-buku', [BukuController::class, 'rekapPeminjamanPerBuku']);