<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kategori; // <-- Import ini wajib ada

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        Kategori::factory()->create(['nama' => 'Novel / Fiksi']);
        Kategori::factory()->create(['nama' => 'Sastra / Sejarah']);
        Kategori::factory()->create(['nama' => 'Pengembangan Diri']);
        Kategori::factory()->create(['nama' => 'Teknologi Informasi']);
    }
}