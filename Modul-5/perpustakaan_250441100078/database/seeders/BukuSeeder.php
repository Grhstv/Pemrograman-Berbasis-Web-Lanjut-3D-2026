<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku; // <-- Import ini wajib ada

class BukuSeeder extends Seeder
{
    public function run(): void
    {
        Buku::factory()->create(['judul' => 'Laskar Pelangi', 'penulis' => 'Andrea Hirata', 'tahun_terbit' => 2005, 'kategori_id' => 1]);
        Buku::factory()->create(['judul' => 'Bumi Manusia', 'penulis' => 'Pramoedya Ananta Toer', 'tahun_terbit' => 1980, 'kategori_id' => 2]);
        Buku::factory()->create(['judul' => 'Filosofi Teras', 'penulis' => 'Henry Manampiring', 'tahun_terbit' => 2018, 'kategori_id' => 3]);
        Buku::factory()->create(['judul' => 'Clean Code', 'penulis' => 'Robert C. Martin', 'tahun_terbit' => 2008, 'kategori_id' => 4]);
        Buku::factory()->create(['judul' => 'Laut Bercerita', 'penulis' => 'Leila S. Chudori', 'tahun_terbit' => 2017, 'kategori_id' => 1]);
    }
}