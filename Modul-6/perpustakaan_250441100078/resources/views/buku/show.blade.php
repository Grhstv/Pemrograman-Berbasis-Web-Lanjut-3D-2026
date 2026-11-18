<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Detail Buku</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <span class="inline-block px-2 py-1 text-sm bg-gray-100 rounded">{{ $buku->kategori?->nama }}</span>
            <h3 class="text-2xl font-bold mt-2">{{ $buku->judul }}</h3>
            <hr class="my-4">
            <p><strong>ID Buku:</strong> {{ $buku->id }}</p>
            <p><strong>Penulis:</strong> {{ $buku->penulis }}</p>
            <p><strong>Tahun Terbit:</strong> {{ $buku->tahun_terbit }}</p>
            <p><strong>Kategori:</strong> {{ $buku->kategori?->nama }}</p>
            <a href="{{ route('buku.daftar') }}" class="inline-block mt-6 underline text-indigo-600">&larr; Kembali ke Daftar Buku</a>
        </div>
    </div></div>
</x-app-layout>
