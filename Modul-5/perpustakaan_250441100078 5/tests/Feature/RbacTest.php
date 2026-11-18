<?php

namespace Tests\Feature;

use App\Models\Anggota;
use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\AkunAnggotaSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** 15 skenario Tugas Praktikum Modul 5. Jalankan: php artisan test --filter=RbacTest */
class RbacTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolePermissionSeeder::class);
        $this->seed(AkunAnggotaSeeder::class);
    }

    private function u(string $email): User
    {
        return User::where('email', $email)->firstOrFail();
    }

    private function admin() { return $this->u('admin@perpustakaan.com'); }
    private function petugas() { return $this->u('petugas@perpustakaan.com'); }
    private function siswa() { return $this->u('siswa@perpustakaan.com'); }

    public function test_01_guest_ke_dashboard_admin_diarahkan_ke_login()
    {
        $this->get('/admin/dashboard')->assertRedirect('/login');
    }

    public function test_02_admin_buka_dashboard_admin()
    {
        $this->actingAs($this->admin())->get('/admin/dashboard')->assertOk();
    }

    public function test_03_admin_crud_buku()
    {
        $a = $this->actingAs($this->admin());
        $kat = Buku::first()->kategori_id;
        $id = $a->postJson('/api/buku', ['judul' => 'Uji', 'penulis' => 'X', 'tahun_terbit' => 2020, 'kategori_id' => $kat])
            ->assertSuccessful()->json('data.id');
        $a->putJson("/api/buku/$id", ['judul' => 'Uji2', 'penulis' => 'X', 'tahun_terbit' => 2021, 'kategori_id' => $kat])->assertSuccessful();
        $a->deleteJson("/api/buku/$id")->assertSuccessful();
    }

    public function test_04_admin_kelola_pengguna_dan_role()
    {
        $this->actingAs($this->admin())->get('/admin/pengguna')->assertOk();
        $this->patch('/admin/pengguna/'.$this->siswa()->id.'/role', ['role' => 'petugas'])->assertSessionHasNoErrors();
        $this->assertTrue($this->siswa()->fresh()->hasRole('petugas'));
    }

    public function test_05_admin_akses_peminjaman_dan_laporan()
    {
        $this->actingAs($this->admin())->get('/kelola-peminjaman')->assertOk();
        $this->get('/laporan')->assertOk();
    }

    public function test_06_petugas_buka_dashboard_petugas()
    {
        $this->actingAs($this->petugas())->get('/petugas/dashboard')->assertOk();
    }

    public function test_07_petugas_kelola_buku()
    {
        $this->actingAs($this->petugas())->get('/kelola-buku')->assertOk();
        $this->postJson('/api/buku', ['judul' => 'P', 'penulis' => 'Q', 'tahun_terbit' => 2022, 'kategori_id' => Buku::first()->kategori_id])->assertSuccessful();
    }

    public function test_08_petugas_proses_peminjaman_dan_pengembalian()
    {
        $p = Peminjaman::create([
            'anggota_id' => $this->siswa()->anggota->id, 'buku_id' => Buku::first()->id,
            'tanggal_pinjam' => now()->toDateString(), 'status' => 'diajukan',
        ]);
        $this->actingAs($this->petugas())->post("/peminjaman/{$p->id}/setujui")->assertRedirect();
        $this->assertSame('dipinjam', $p->fresh()->status);
        $this->post("/peminjaman/{$p->id}/kembali")->assertRedirect();
        $this->assertSame('dikembalikan', $p->fresh()->status);
        $this->get('/laporan')->assertOk();
    }

    public function test_09_petugas_ditolak_halaman_pengguna_dan_role()
    {
        $this->actingAs($this->petugas())->get('/admin/pengguna')->assertForbidden();
        $this->patch('/admin/pengguna/'.$this->siswa()->id.'/role', ['role' => 'admin'])->assertForbidden();
    }

    public function test_10_siswa_lihat_daftar_buku()
    {
        $this->actingAs($this->siswa())->get('/buku')->assertOk();
        $this->getJson('/api/buku')->assertOk();
    }

    public function test_11_siswa_lihat_dan_ubah_profil_sendiri()
    {
        $this->actingAs($this->siswa())->get('/profile')->assertOk();
        $this->patch('/profile', ['name' => 'Nama Baru', 'email' => 'siswa@perpustakaan.com'])->assertSessionHasNoErrors();
        $this->assertSame('Nama Baru', $this->siswa()->name);
    }

    public function test_12_siswa_lihat_riwayat_sendiri()
    {
        $this->actingAs($this->siswa())->get('/riwayat-peminjaman')->assertOk();
    }

    public function test_13_siswa_ditolak_dashboard_admin_dan_kelola_buku()
    {
        $this->actingAs($this->siswa())->get('/admin/dashboard')->assertForbidden();
        $this->get('/kelola-buku')->assertForbidden();
        $this->postJson('/api/buku', ['judul' => 'x', 'penulis' => 'y', 'tahun_terbit' => 2020, 'kategori_id' => 1])->assertForbidden();
        $this->get('/admin/pengguna')->assertForbidden();
    }

    public function test_14_siswa_ditolak_riwayat_siswa_lain()
    {
        $lain = $this->u('siswa2@perpustakaan.com');
        $this->actingAs($this->siswa())->get("/pengguna/{$lain->id}/riwayat-peminjaman")->assertForbidden();
        $this->get('/pengguna/'.$this->siswa()->id.'/riwayat-peminjaman')->assertOk();
    }

    public function test_15_logout_lalu_halaman_terproteksi_minta_login()
    {
        $this->actingAs($this->siswa())->post('/logout');
        $this->assertGuest();
        $this->get('/dashboard')->assertRedirect('/login');
    }

    public function test_registrasi_selalu_siswa_dan_field_role_diabaikan()
    {
        $this->post('/register', [
            'name' => 'Baru', 'email' => 'baru@x.com', 'password' => 'Rahasia123!',
            'password_confirmation' => 'Rahasia123!', 'role' => 'admin',
        ]);
        $u = User::where('email', 'baru@x.com')->first();
        $this->assertTrue($u->hasRole('siswa'));
        $this->assertFalse($u->hasRole('admin'));
        $this->assertNotSame('Rahasia123!', $u->password);
        $this->assertNotNull(Anggota::where('user_id', $u->id)->first());
    }
}
