<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Buku;

use App\Http\Requests\StoreBukuRequest;
use App\Http\Requests\UpdateBukuRequest;
use App\Http\Resources\BukuResource;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    // GET /api/buku
    public function index(Request $request)
    {
        $query = Buku::with('kategori');

        if ($request->has('cari') && $request->cari != '') {
            $cari = $request->cari;
            $query->where(function ($q) use ($cari) {
                $q->where('judul', 'like', "%{$cari}%")
                  ->orWhere('penulis', 'like', "%{$cari}%");
            });
        }

        if ($request->has('kategori') && $request->kategori != '') {
            $query->where('kategori_id', $request->kategori);
        }

        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_terbit', $request->tahun);
        }

        $query->orderBy('created_at', 'desc');

        $bukus = $query->get();

        return response()->json([
            'message' => 'Berhasil mengambil data buku',
            'data'    => BukuResource::collection($bukus)
        ], 200);
    }

    // POST /api/buku
    public function store(StoreBukuRequest $request)
    {
        $buku = Buku::create($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Buku berhasil ditambahkan',
            'data'    => new BukuResource($buku)
        ], 201);
    }

    // GET /api/buku/{id}
    public function show(int|string $id)
    {
        $buku = Buku::with('kategori')->find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        return response()->json([
            'message' => 'Detail buku ditemukan',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    // PUT /api/buku/{id}
    public function update(UpdateBukuRequest $request, int|string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->update($request->validated());
        $buku->load('kategori');

        return response()->json([
            'message' => 'Data buku berhasil diperbarui',
            'data'    => new BukuResource($buku)
        ], 200);
    }

    // DELETE /api/buku/{id}
    public function destroy(int|string $id)
    {
        $buku = Buku::find($id);

        if (!$buku) {
            return response()->json(['message' => 'Buku tidak ditemukan'], 404);
        }

        $buku->delete();

        return response()->json([
            'message' => 'Buku berhasil dihapus'
        ], 200);
    }
}

