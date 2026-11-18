<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Anggota; // <-- Import Model Anggota
use App\Models\Buku;    // <-- Import Model Buku

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Peminjaman>
 */
class PeminjamanFactory extends Factory
{
    public function definition(): array
    {
        return [
            'anggota_id' => Anggota::inRandomOrder()->first()->id ?? Anggota::factory(),
            'buku_id' => Buku::inRandomOrder()->first()->id ?? Buku::factory(),
            'tanggal_pinjam' => fake()->dateTimeBetween('-1 month', 'now'),
            'tanggal_kembali' => fake()->optional()->dateTimeBetween('now', '+1 week'),
        ];
    }
}