<?php

namespace App\Http\Controllers;

use App\Models\Buku;

class BukuController extends Controller
{
    public function index()
    {
        $buku = Buku::with('kategori')->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'judul' => $item->judul,
                'penulis' => $item->penulis,
                'tahun_terbit' => $item->tahun_terbit,
                'kategori' => $item->kategori->nama ?? 'Umum',
            ];
        });

        return view('buku.index', compact('buku'));
    }

    public function show(int|string$id)
    {
        $data = Buku::with('kategori')->find($id);

        $buku = $data ? [
            'id' => $data->id,
            'judul' => $data->judul,
            'penulis' => $data->penulis,
            'tahun_terbit' => $data->tahun_terbit,
            'kategori' => $data->kategori->nama ?? 'Umum',
        ] : null;

        return view('buku.show', compact('buku'));
    }
}