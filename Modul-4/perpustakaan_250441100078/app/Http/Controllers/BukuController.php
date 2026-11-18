<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    // Menampilkan daftar buku untuk halaman web Modul 2
    public function index()
    {
        $bukus = Buku::with('kategori')->get();
        
        return view('buku.index', compact('bukus'));
    }

    // Menampilkan detail buku untuk halaman web Modul 2
    public function show(int|string $id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->findOrFail($id);

        return view('buku.show', compact('buku'));
    }
}