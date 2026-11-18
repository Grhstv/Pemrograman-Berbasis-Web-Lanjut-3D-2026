<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('login'));

// ---- Semua halaman di bawah ini wajib login (middleware auth) ----
Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profil: ProfileController memakai $request->user(), jadi hanya data milik sendiri.
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Admin
    Route::middleware('can:akses dashboard admin')->get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::middleware('can:kelola data pengguna')->get('/admin/pengguna', [UserController::class, 'index'])->name('admin.pengguna');
    Route::middleware('can:kelola role pengguna')->patch('/admin/pengguna/{user}/role', [UserController::class, 'updateRole'])->name('admin.pengguna.role');

    // Petugas
    Route::middleware('can:akses dashboard petugas')->get('/petugas/dashboard', [DashboardController::class, 'petugas'])->name('petugas.dashboard');

    // Buku (daftar + CRUD lewat /api/buku)
    Route::middleware('can:lihat daftar buku')->get('/buku', fn () => view('buku.index'))->name('buku.index');
    Route::middleware('can:kelola data buku')->get('/kelola-buku', fn () => view('buku.index'))->name('buku.kelola');

    // Peminjaman & pengembalian (staf)
    Route::middleware('can:kelola peminjaman')->get('/kelola-peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.kelola');
    Route::middleware('can:memproses persetujuan peminjaman')->post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::middleware('can:mencatat pengembalian buku')->post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembali');

    // Peminjaman (siswa)
    Route::middleware('can:mengajukan peminjaman buku')->post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::middleware('can:melihat riwayat peminjaman sendiri')->get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('riwayat.saya');
    // Cek kepemilikan dilakukan di controller (identitas login = pemilik data).
    Route::get('/pengguna/{user}/riwayat-peminjaman', [PeminjamanController::class, 'riwayatUser'])->name('riwayat.user');

    // Laporan
    Route::middleware('can:lihat laporan perpustakaan')->get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';