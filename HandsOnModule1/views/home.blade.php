@extends('layouts.app')

@section('content')

    {{-- HERO --}}
    <section class="hero">

        <div class="hero-content">

            <div class="hero-badge">
                👋 Selamat Datang di Warung Kita
            </div>

            <h1>
                Makanan Sederhana,
                <span>Rasa Luar Biasa.</span>
            </h1>

            <p>
                Temukan berbagai makanan dan minuman favorit
                yang dibuat dengan bahan berkualitas untuk Anda.
            </p>

            <a href="{{ route('menu.index') }}" class="btn-primary">
                Lihat Menu →
            </a>

        </div>

        <div class="hero-food">
            🍛
        </div>

    </section>


    {{-- FITUR --}}
    <section class="section">

        <div class="section-title">

            <h2>Kenapa Warung Kita?</h2>

            <p>
                Sederhana, lezat, dan cocok untuk semua.
            </p>

        </div>


        <div class="features">

            <div class="feature-card">

                <div class="feature-icon">
                    🍜
                </div>

                <h3>Menu Beragam</h3>

                <p>
                    Berbagai pilihan makanan dan minuman
                    untuk menemani hari Anda.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    ⭐
                </div>

                <h3>Rasa Berkualitas</h3>

                <p>
                    Dibuat dengan bahan pilihan
                    dan cita rasa terbaik.
                </p>

            </div>


            <div class="feature-card">

                <div class="feature-icon">
                    💰
                </div>

                <h3>Harga Bersahabat</h3>

                <p>
                    Nikmati makanan enak
                    dengan harga yang terjangkau.
                </p>

            </div>

        </div>

    </section>

@endsection