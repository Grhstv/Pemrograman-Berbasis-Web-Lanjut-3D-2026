<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Peminjaman; // <-- Import ini wajib ada

class PeminjamanSeeder extends Seeder
{
    public function run(): void
    {
        Peminjaman::factory(5)->create();
    }
}