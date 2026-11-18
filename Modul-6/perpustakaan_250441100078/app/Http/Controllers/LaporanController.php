<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.index', [
            'totalBuku' => Buku::count(),
            'diajukan' => Peminjaman::where('status', 'diajukan')->count(),
            'dipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'dikembalikan' => Peminjaman::where('status', 'dikembalikan')->count(),
            'terbaru' => Peminjaman::with(['anggota', 'buku'])->latest()->take(10)->get(),
        ]);
    }
}
