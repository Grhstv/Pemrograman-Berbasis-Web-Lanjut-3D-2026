<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BukuResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'kategori_id' => $this->kategori_id,
            'kategori'    => $this->kategori->nama ?? $this->kategori->nama_kategori ?? 'Umum',
            'judul'       => $this->judul ?? $this->judul_buku ?? '',
            'penulis'     => $this->penulis ?? $this->penulis_buku ?? '',
            'tahun'       => $this->tahun_terbit ?? $this->tahun ?? '',
        ];
    }
}