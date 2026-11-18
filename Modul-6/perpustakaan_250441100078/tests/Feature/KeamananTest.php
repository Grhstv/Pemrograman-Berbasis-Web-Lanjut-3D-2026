<?php

namespace Tests\Feature;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Database\Seeders\AkunAnggotaSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Pengujian keamanan Modul 6 (keadaan akhir setelah Soal 2-5).
 * Catatan: token CSRF (419) dinonaktifkan Laravel saat tes berjalan, jadi diuji manual lewat Postman.
 * Jalankan: php artisan test --filter=KeamananTest  (pakai database tes terpisah, bukan database asli!)
 */
class KeamananTest extends TestCase
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

    private function peminjamanSiswa(): Peminjaman
    {
        return Peminjaman::create([
            'anggota_id' => $this->u('siswa@perpustakaan.com')->anggota->id,
            'buku_id' => Buku::first()->id,
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'dipinjam',
        ]);
    }

    public function test_01_guest_ke_detail_peminjaman_diarahkan_ke_login()
    {
        $p = $this->peminjamanSiswa();
        $this->get('/peminjaman/'.$p->id)->assertRedirect('/login');
    }

    public function test_02_judul_berisi_script_tampil_sebagai_teks()
    {
        Buku::create(['kategori_id' => 4, 'judul' => '<script>alert(1)</script>', 'penulis' => 'X', 'tahun_terbit' => 2024]);

        $this->actingAs($this->u('admin@perpustakaan.com'))->get('/daftar-buku')
            ->assertOk()
            ->assertDontSee('<script>alert(1)</script>', false)
            ->assertSee('&lt;script&gt;alert(1)&lt;/script&gt;', false);
    }

    public function test_03_buku_cari_memakai_binding()
    {
        $this->actingAs($this->u('admin@perpustakaan.com'));
        $this->getJson('/buku-cari?judul='.urlencode("' OR '1'='1"))->assertOk();
        $this->assertSame([], $this->getJson('/buku-cari?judul='.urlencode("' OR '1'='1"))->json());
        $this->assertCount(1, $this->getJson('/buku-cari?judul='.urlencode(Buku::first()->judul))->json());
    }

    public function test_05_hapus_lewat_get_tidak_ada_dan_delete_berfungsi()
    {
        $id = Buku::first()->id;
        $this->actingAs($this->u('admin@perpustakaan.com'))->get("/buku/$id/hapus")->assertNotFound();
        $this->assertDatabaseHas('bukus', ['id' => $id]);

        $this->delete("/buku/$id")->assertRedirect();
        $this->assertDatabaseMissing('bukus', ['id' => $id]);
    }

    public function test_07_siswa_ditolak_buku_create_staf_diizinkan()
    {
        $this->actingAs($this->u('siswa@perpustakaan.com'))->get('/buku/create')->assertForbidden();
        $this->actingAs($this->u('admin@perpustakaan.com'))->get('/buku/create')->assertOk();
        $this->actingAs($this->u('petugas@perpustakaan.com'))->get('/buku/create')->assertOk();
    }

    public function test_08_09_10_policy_detail_peminjaman()
    {
        $p = $this->peminjamanSiswa();

        $this->actingAs($this->u('siswa@perpustakaan.com'))->get('/peminjaman/'.$p->id)->assertOk();
        $this->actingAs($this->u('siswa2@perpustakaan.com'))->get('/peminjaman/'.$p->id)->assertForbidden();
        $this->actingAs($this->u('petugas@perpustakaan.com'))->get('/peminjaman/'.$p->id)->assertOk();
        $this->actingAs($this->u('admin@perpustakaan.com'))->get('/peminjaman/'.$p->id)->assertOk();
    }

    public function test_12_password_bawaan_admin_tidak_berlaku()
    {
        $this->post('/login', ['email' => 'admin@perpustakaan.com', 'password' => 'password123'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();
    }
}
