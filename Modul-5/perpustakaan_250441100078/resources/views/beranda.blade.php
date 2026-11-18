@extends('layouts.app')

@section('judul', 'Beranda - PustakaDigital')

@section('konten')
<div class="hero">
    <h2>Selamat Datang di PustakaDigital</h2>
    <p>Sistem manajemen dan koleksi pustaka digital praktis, cepat, dan mudah diakses.</p>
    <a href="{{ route('buku.index') }}" class="btn btn-hero">Eksplorasi Buku Baru</a>
</div>
@endsection