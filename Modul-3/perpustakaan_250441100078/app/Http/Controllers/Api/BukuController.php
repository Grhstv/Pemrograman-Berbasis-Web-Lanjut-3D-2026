<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Kategori;
use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET /api/buku (dengan pencarian, filter, dan pagination)
    public function index(Request $request)
    {
        $query = Buku::with('kategori')->withCount('peminjaman');

        if ($request->has('cari')) {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->has('kategori')) {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('tahun')) {
            $query->where('tahun_terbit', $request->tahun);
        }

        $query->orderBy('created_at', 'desc');

        $bukus = $query->paginate(10)->withQueryString();

        return response()->json([
            'message' => 'Berhasil mengambil data buku',
            'data' => $bukus
        ], 200);
    }

    // POST /api/buku
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data' => $buku
        ], 201);
    }

    // GET /api/buku/{id}
    public function show(int|string $id)
    {
        $buku = Buku::with(['kategori', 'peminjaman'])->find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        return response()->json([
            'message' => 'Detail buku ditemukan',
            'data' => $buku
        ], 200);
    }

    // PUT /api/buku/{id}
    public function update(UpdateBukuRequest $request, int|string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->update($request->validated());

        return response()->json([
            'message' => 'Data buku berhasil diperbarui',
            'data' => $buku
        ], 200);
    }

    // DELETE /api/buku/{id}
    public function destroy(int|string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'not found'], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }

    // GET /api/rekap/buku-per-kategori
    public function rekapBukuPerKategori()
    {
        $rekap = Kategori::withCount('bukus')->get()->map(function ($kategori) {
            return [
                'kategori' => $kategori->nama,
                'jumlah_buku' => $kategori->bukus_count
            ];
        });

        return response()->json([
            'message' => 'Rekap jumlah buku per kategori',
            'data' => $rekap
        ], 200);
    }

    // GET /api/rekap/peminjaman-per-buku
    public function rekapPeminjamanPerBuku()
    {
        $rekap = Buku::withCount('peminjaman')->get()->map(function ($buku) {
            return [
                'judul' => $buku->judul,
                'total_dipinjam' => $buku->peminjaman_count
            ];
        });

        return response()->json([
            'message' => 'Rekap total peminjaman per buku',
            'data' => $rekap
        ], 200);
    }
}