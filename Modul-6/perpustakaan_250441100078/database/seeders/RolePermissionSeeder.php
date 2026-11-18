<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Buku;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1. ISI DATA KATEGORI (Agar foreign key kategori_id tidak error)
        Kategori::firstOrCreate(['id' => 1], ['nama' => 'Novel / Fiksi']);
        Kategori::firstOrCreate(['id' => 2], ['nama' => 'Sastra / Sejarah']);
        Kategori::firstOrCreate(['id' => 3], ['nama' => 'Pengembangan Diri']);
        Kategori::firstOrCreate(['id' => 4], ['nama' => 'Teknologi Informasi']);

        // 2. ISI DATA BUKU DUMMY AWAL
        Buku::firstOrCreate([
            'judul' => 'Pemrograman Web Lanjut dengan Laravel',
            'penulis' => 'Gerhant Steve',
            'tahun_terbit' => 2024,
            'kategori_id' => 4,
        ]);

        // 3. BUAT SEMUA PERMISSION
        $permissions = [
            'akses dashboard admin',
            'akses dashboard petugas',
            'lihat daftar buku',
            'kelola data buku',
            'kelola data pengguna',
            'kelola role pengguna',
            'kelola peminjaman',
            'kelola pengembalian',
            'mengajukan peminjaman buku',
            'memproses persetujuan peminjaman',
            'mencatat pengembalian buku',
            'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri',
            'lihat laporan perpustakaan',
            'lihat profil sendiri',
            'ubah profil sendiri'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // 4. BUAT ROLE DAN SET PERMISSION
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->givePermissionTo(Permission::all());

        $petugasRole = Role::firstOrCreate(['name' => 'petugas']);
        $petugasRole->givePermissionTo([
            'akses dashboard petugas', 'lihat daftar buku', 'kelola data buku', 
            'kelola peminjaman', 'kelola pengembalian', 'mengajukan peminjaman buku', 
            'memproses persetujuan peminjaman', 'mencatat pengembalian buku', 
            'melihat status peminjaman sendiri', 'melihat riwayat peminjaman sendiri', 
            'lihat laporan perpustakaan', 'lihat profil sendiri', 'ubah profil sendiri'
        ]);

        $siswaRole = Role::firstOrCreate(['name' => 'siswa']);
        $siswaRole->givePermissionTo([
            'lihat daftar buku', 'mengajukan peminjaman buku', 'melihat status peminjaman sendiri',
            'melihat riwayat peminjaman sendiri', 'lihat profil sendiri', 'ubah profil sendiri'
        ]);

        // 5. BUAT AKUN DEFAULT
        // [Soal 5c] Password admin TIDAK lagi 'password123'. Diambil dari .env (ADMIN_PASSWORD);
        // bila kosong dibuat acak. updateOrCreate agar akun admin yang sudah ada ikut diganti.
        $passwordAdmin = env('ADMIN_PASSWORD') ?: Str::password(20);
        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn('ADMIN_PASSWORD belum diisi di .env. Password admin acak: ' . $passwordAdmin);
        }

        $admin = User::updateOrCreate([
            'email' => 'admin@perpustakaan.com'
        ], [
            'name' => 'Administrator',
            'password' => Hash::make($passwordAdmin)
        ]);
        $admin->assignRole('admin');

        $petugas = User::firstOrCreate([
            'email' => 'petugas@perpustakaan.com'
        ], [
            'name' => 'Petugas Perpustakaan',
            'password' => Hash::make('password123')
        ]);
        $petugas->assignRole('petugas');

        $siswa = User::firstOrCreate([
            'email' => 'siswa@perpustakaan.com'
        ], [
            'name' => 'Siswa Pengguna',
            'password' => Hash::make('password123')
        ]);
        $siswa->assignRole('siswa');
    }
}
