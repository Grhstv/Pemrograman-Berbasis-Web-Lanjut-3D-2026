<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Riwayat peminjaman satu pengguna: boleh dilihat pemiliknya sendiri
     * atau staf yang berhak mengelola peminjaman.
     */
    public function viewRiwayat(User $actor, User $pemilik): bool
    {
        return $actor->id === $pemilik->id || $actor->can('kelola peminjaman');
    }
}
