<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\BukuController;
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

    // Buku (daftar JS + CRUD lewat /api/buku)
    Route::middleware('can:lihat daftar buku')->get('/buku', fn () => view('buku.index'))->name('buku.index');

    // [Soal 3] Semua aksi pengelolaan buku dijaga Gate 'kelola-buku'
    Route::middleware('can:kelola-buku')->group(function () {
        Route::get('/kelola-buku', fn () => view('buku.index'))->name('buku.kelola');
        Route::get('/buku/create', [BukuController::class, 'create'])->name('buku.create');
        Route::post('/buku', [BukuController::class, 'store'])->name('buku.store');
        Route::get('/buku/{buku}/edit', [BukuController::class, 'edit'])->name('buku.edit');
        Route::put('/buku/{buku}', [BukuController::class, 'update'])->name('buku.update');
        Route::delete('/buku/{buku}', [BukuController::class, 'destroy'])->name('buku.destroy');
    });

    Route::get('/daftar-buku', [BukuController::class, 'daftar'])->name('buku.daftar');
    Route::get('/buku-cari', [BukuController::class, 'cari'])->name('buku.cari');
    // /buku/create harus terdaftar lebih dulu daripada /buku/{id}
    Route::get('/buku/{id}', [BukuController::class, 'show'])->name('buku.show');

    // Peminjaman & pengembalian (staf)
    Route::middleware('can:kelola peminjaman')->get('/kelola-peminjaman', [PeminjamanController::class, 'index'])->name('peminjaman.kelola');
    Route::middleware('can:memproses persetujuan peminjaman')->post('/peminjaman/{peminjaman}/setujui', [PeminjamanController::class, 'setujui'])->name('peminjaman.setujui');
    Route::middleware('can:mencatat pengembalian buku')->post('/peminjaman/{peminjaman}/kembali', [PeminjamanController::class, 'kembalikan'])->name('peminjaman.kembali');

    // Peminjaman (siswa)
    Route::middleware('can:mengajukan peminjaman buku')->post('/peminjaman', [PeminjamanController::class, 'store'])->name('peminjaman.store');
    Route::middleware('can:melihat riwayat peminjaman sendiri')->get('/riwayat-peminjaman', [PeminjamanController::class, 'riwayatSaya'])->name('riwayat.saya');
    Route::get('/pengguna/{user}/riwayat-peminjaman', [PeminjamanController::class, 'riwayatUser'])->name('riwayat.user');
    Route::get('/peminjaman/{id}', [PeminjamanController::class, 'show'])->name('peminjaman.show'); // A4

    // Laporan
    Route::middleware('can:lihat laporan perpustakaan')->get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';