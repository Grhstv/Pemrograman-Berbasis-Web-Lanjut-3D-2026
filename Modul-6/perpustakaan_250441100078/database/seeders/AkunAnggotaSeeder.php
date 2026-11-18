<?php

namespace Database\Seeders;

use App\Models\Anggota;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/** Menautkan akun siswa ke data anggota (+ satu siswa kedua untuk pengujian). */
class AkunAnggotaSeeder extends Seeder
{
    public function run(): void
    {
        $siswa2 = User::firstOrCreate(
            ['email' => 'siswa2@perpustakaan.com'],
            ['name' => 'Siswa Dua', 'password' => Hash::make('password123')]
        );
        $siswa2->syncRoles('siswa');

        foreach (['siswa@perpustakaan.com', 'siswa2@perpustakaan.com'] as $email) {
            $u = User::where('email', $email)->first();
            if ($u) {
                Anggota::updateOrCreate(
                    ['email' => $u->email],
                    ['user_id' => $u->id, 'nama' => $u->name, 'no_telepon' => '-']
                );
            }
        }
    }
}
