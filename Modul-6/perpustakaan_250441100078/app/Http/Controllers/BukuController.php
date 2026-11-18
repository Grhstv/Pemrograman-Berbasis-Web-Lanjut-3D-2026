<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BukuController extends Controller
{
    // Menampilkan daftar buku untuk halaman web Modul 2
    public function index()
    {
        $bukus = Buku::with('kategori')->get();

        return view('buku.index', compact('bukus'));
    }

    // Daftar buku yang dirender di server (Blade), dipakai pada Modul 6
    public function daftar()
    {
        $bukus = Buku::with('kategori')->orderBy('id')->get();

        return view('buku.daftar', compact('bukus'));
    }

    // Menampilkan detail buku untuk halaman web Modul 2
    public function show(int|string $id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->findOrFail($id);

        return view('buku.show', compact('buku'));
    }

    // [Soal 2b] AMAN: placeholder ? dengan nilai dikirim terpisah (query binding)
    public function cari(Request $request)
    {
        $judul = $request->query('judul');

        $hasil = DB::select(
            'SELECT id, judul, penulis, tahun_terbit FROM bukus WHERE judul = ?',
            [$judul]
        );

        return response()->json($hasil);
    }

    // [Soal 3] Tambah, ubah, hapus buku lewat halaman web
    public function create()
    {
        return view('buku.form', [
            'buku' => new Buku(),
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function store(Request $request)
    {
        Buku::create($this->validasi($request));

        return redirect()->route('buku.daftar')->with('success', 'Buku ditambahkan.');
    }

    public function edit(Buku $buku)
    {
        return view('buku.form', [
            'buku' => $buku,
            'kategoris' => Kategori::orderBy('nama')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($this->validasi($request));

        return redirect()->route('buku.daftar')->with('success', 'Buku diperbarui.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'judul' => ['required', 'string', 'max:255'],
            'penulis' => ['required', 'string', 'max:255'],
            'tahun_terbit' => ['required', 'integer', 'min:1000', 'max:' . date('Y')],
        ]);
    }

    // Dipanggil lewat DELETE /buku/{buku} (token CSRF wajib)
    public function destroy(Buku $buku)
    {
        $buku->delete();

        return redirect()->route('buku.daftar')->with('success', 'Buku dihapus.');
    }
}
