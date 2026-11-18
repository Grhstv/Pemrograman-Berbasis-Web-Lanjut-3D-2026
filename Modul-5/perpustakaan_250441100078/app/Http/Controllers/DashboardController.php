<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /** Titik masuk setelah login: arahkan sesuai hak akses. */
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->can('akses dashboard admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user->can('akses dashboard petugas')) {
            return redirect()->route('petugas.dashboard');
        }

        return view('dashboard');
    }

    public function admin()
    {
        return view('admin.dashboard', [
            'totalBuku' => Buku::count(),
            'totalPengguna' => User::count(),
            'sedangDipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'menunggu' => Peminjaman::where('status', 'diajukan')->count(),
        ]);
    }

    public function petugas()
    {
        return view('petugas.dashboard', [
            'totalBuku' => Buku::count(),
            'sedangDipinjam' => Peminjaman::where('status', 'dipinjam')->count(),
            'menunggu' => Peminjaman::where('status', 'diajukan')->count(),
        ]);
    }
}
