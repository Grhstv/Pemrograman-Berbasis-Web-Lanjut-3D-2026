<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class PeminjamanController extends Controller
{
    /** Petugas/Admin: semua data peminjaman & pengembalian. */
    public function index()
    {
        return view('peminjaman.index', [
            'peminjamans' => Peminjaman::with(['anggota', 'buku'])->latest()->get(),
        ]);
    }

    /** Siswa: mengajukan peminjaman atas nama dirinya sendiri. */
    public function store(Request $request)
    {
        $data = $request->validate(['buku_id' => ['required', 'exists:bukus,id']]);

        $anggota = $request->user()->anggota;
        abort_if(! $anggota, 403, 'Akun Anda belum terhubung dengan data anggota.');

        $aktif = Peminjaman::where('anggota_id', $anggota->id)
            ->where('buku_id', $data['buku_id'])
            ->whereIn('status', ['diajukan', 'dipinjam'])
            ->exists();

        if ($aktif) {
            return back()->with('error', 'Buku ini sudah Anda ajukan atau sedang Anda pinjam.');
        }

        Peminjaman::create([
            'anggota_id' => $anggota->id,   // dari identitas login, bukan dari input
            'buku_id' => $data['buku_id'],
            'tanggal_pinjam' => now()->toDateString(),
            'status' => 'diajukan',
        ]);

        return redirect()->route('riwayat.saya')->with('success', 'Pengajuan peminjaman terkirim.');
    }

    public function setujui(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'diajukan') {
            return back()->with('error', 'Peminjaman ini tidak berstatus diajukan.');
        }
        $peminjaman->update(['status' => 'dipinjam', 'tanggal_pinjam' => now()->toDateString()]);

        return back()->with('success', 'Peminjaman disetujui.');
    }

    public function kembalikan(Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'dipinjam') {
            return back()->with('error', 'Buku ini tidak sedang dipinjam.');
        }
        $peminjaman->update(['status' => 'dikembalikan', 'tanggal_kembali' => now()->toDateString()]);

        return back()->with('success', 'Pengembalian dicatat.');
    }

    /** [Soal 4] Dilindungi PeminjamanPolicy@view */
    public function show(int $id)
    {
        $peminjaman = Peminjaman::with(['anggota', 'buku'])->findOrFail($id);

        Gate::authorize('view', $peminjaman);

        return view('peminjaman.show', compact('peminjaman'));
    }

    /** Riwayat milik user yang sedang login. */
    public function riwayatSaya(Request $request)
    {
        return $this->tampilRiwayat($request->user());
    }

    /** Riwayat user lain: aturan ada di UserPolicy@viewRiwayat. */
    public function riwayatUser(Request $request, User $user)
    {
        Gate::authorize('viewRiwayat', $user);   // UserPolicy

        return $this->tampilRiwayat($user);
    }

    private function tampilRiwayat(User $user)
    {
        $riwayat = $user->anggota
            ? Peminjaman::with('buku')->where('anggota_id', $user->anggota->id)->latest()->get()
            : collect();

        return view('peminjaman.riwayat', ['pemilik' => $user, 'riwayat' => $riwayat]);
    }
}
