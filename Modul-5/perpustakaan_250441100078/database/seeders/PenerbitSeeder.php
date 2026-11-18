<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Penerbit; // import

class PenerbitSeeder extends Seeder
{
    public function run(): void
    {
        Penerbit::factory(5)->create(); // buat dum
    }
}