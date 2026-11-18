@extends('layouts.app')

@section('judul', isset($buku) ? $buku['judul'] : 'Buku Tidak Ditemukan')

@section('konten')
<div class="detail-container">
    @if ($buku)
        <div class="detail-card">
            <span class="badge">{{ $buku['kategori'] }}</span>
            <h2>{{ $buku['judul'] }}</h2>
            <hr>
            <div class="detail-info">
                <p><strong>ID Buku:</strong> {{ $buku['id'] }}</p>
                <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
                <p><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>
                <p><strong>Kategori:</strong> {{ $buku['kategori'] }}</p>
            </div>
            <br>
            <a href="{{ route('buku.index') }}" class="btn btn-secondary">&larr; Kembali ke Daftar Buku</a>
        </div>
    @else
        <div class="alert alert-danger">
            <h2>⚠️ Buku Tidak Ditemukan!</h2>
            <p>Maaf, buku dengan ID yang Anda cari tidak terdaftar dalam sistem perpustakaan kami.</p>
            <br>
            <a href="{{ route('buku.index') }}" class="btn btn-primary">&larr; Kembali ke Daftar Buku</a>
        </div>
    @endif
</div>
@endsection