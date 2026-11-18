@extends('layouts.app')

@section('judul', 'Koleksi Buku - PustakaDigital')

@section('konten')
<div class="page-header">
    <h2>Koleksi Buku Perpustakaan</h2>
    <p>Temukan berbagai referensi buku menarik yang tersedia.</p>
</div>

<div class="grid-buku">
    @forelse ($buku as $item)
        <x-kartu-buku 
            :id="$item['id']" 
            :judul="$item['judul']" 
            :penulis="$item['penulis']" 
            :tahun="$item['tahun_terbit']"
        >
            <!-- Using Named Slot -->
            <x-slot:extra>
                <span class="badge">{{ $item['kategori'] }}</span>
            </x-slot:extra>
        </x-kartu-buku>
    @empty
        <p>Tidak ada data buku yang tersedia saat ini.</p>
    @endforelse
</div>
@endsection