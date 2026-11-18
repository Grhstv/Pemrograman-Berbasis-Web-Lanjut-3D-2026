@extends('layouts.app')

@section('title', 'Tentang - Warung Kita')

@section('content')

<section class="mx-auto max-w-4xl px-6 py-20">

    <div class="text-center">

        <span class="text-sm font-semibold uppercase tracking-wider text-orange-600">
            Tentang Kami
        </span>

        <h1 class="mt-3 text-4xl font-bold text-gray-900">
            Mengenal Warung Kita
        </h1>

    </div>


    <div class="mt-12 rounded-2xl bg-white p-8 shadow-sm">

        <p class="leading-relaxed text-gray-600">
            Warung Kita adalah sebuah website sederhana yang digunakan
            untuk menampilkan berbagai menu makanan dan minuman.
        </p>

        <p class="mt-5 leading-relaxed text-gray-600">
            Website ini dibuat sebagai contoh penerapan Laravel,
            MVC, Routing, Controller, dan Blade Template.
        </p>

        <div class="mt-8 grid gap-4 sm:grid-cols-3">

            <div class="rounded-xl bg-orange-50 p-5 text-center">
                <div class="text-3xl">🍜</div>
                <p class="mt-2 font-semibold">Makanan</p>
            </div>

            <div class="rounded-xl bg-orange-50 p-5 text-center">
                <div class="text-3xl">🥤</div>
                <p class="mt-2 font-semibold">Minuman</p>
            </div>

            <div class="rounded-xl bg-orange-50 p-5 text-center">
                <div class="text-3xl">❤️</div>
                <p class="mt-2 font-semibold">Pelayanan</p>
            </div>

        </div>

    </div>

</section>

@endsection