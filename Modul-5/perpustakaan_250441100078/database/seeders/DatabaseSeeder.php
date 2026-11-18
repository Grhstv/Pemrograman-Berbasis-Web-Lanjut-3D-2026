<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            KategoriSeeder::class,
            AnggotaSeeder::class,
            BukuSeeder::class,
            PeminjamanSeeder::class,
            PenerbitSeeder::class,
            RolePermissionSeeder::class,   // role, permission, akun admin/petugas/siswa
            AkunAnggotaSeeder::class,      // tautkan akun siswa ke anggota
        ]);
    }
}
