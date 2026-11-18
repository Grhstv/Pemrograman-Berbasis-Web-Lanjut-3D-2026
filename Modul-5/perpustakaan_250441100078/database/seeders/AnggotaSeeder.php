<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Anggota; // <-- Import ini wajib ada

class AnggotaSeeder extends Seeder
{
    public function run(): void
    {
        Anggota::factory(5)->create();
    }
}