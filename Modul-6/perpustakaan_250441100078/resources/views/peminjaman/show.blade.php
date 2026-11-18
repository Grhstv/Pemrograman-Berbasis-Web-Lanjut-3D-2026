<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Peminjaman #{{ $peminjaman->id }}</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <p><strong>Anggota:</strong> {{ $peminjaman->anggota?->nama }}</p>
            <p><strong>Email:</strong> {{ $peminjaman->anggota?->email }}</p>
            <p><strong>Buku:</strong> {{ $peminjaman->buku?->judul }}</p>
            <p><strong>Tanggal Pinjam:</strong> {{ $peminjaman->tanggal_pinjam }}</p>
            <p><strong>Tanggal Kembali:</strong> {{ $peminjaman->tanggal_kembali ?? '-' }}</p>
            <p><strong>Status:</strong> {{ ucfirst($peminjaman->status) }}</p>
        </div>
    </div></div>
</x-app-layout>
