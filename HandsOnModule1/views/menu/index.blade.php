@extends('layouts.app')

@section('title', 'Menu - Warung Kita')

@section('content')

<section class="mx-auto max-w-7xl px-6 py-16">

    <div class="text-center">

        <span class="text-sm font-semibold uppercase tracking-wider text-orange-600">
            Pilihan Kami
        </span>

        <h1 class="mt-2 text-4xl font-bold text-gray-900">
            Daftar Menu
        </h1>

        <p class="mt-3 text-gray-600">
            Pilih makanan favoritmu hari ini.
        </p>

    </div>


    @if (count($menus) > 0)

        <div class="mt-12 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">

            @foreach ($menus as $menu)

                <div class="overflow-hidden rounded-2xl bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-lg">

                    {{-- Gambar sementara --}}
                    <div class="flex h-48 items-center justify-center bg-orange-100 text-7xl">
                        🍛
                    </div>


                    <div class="p-6">

                        <div class="flex items-start justify-between gap-4">

                            <h2 class="text-xl font-bold text-gray-900">
                                {{ $menu['nama'] }}
                            </h2>

                            @if ($menu['tersedia'])

                                <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                    Tersedia
                                </span>

                            @else

                                <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                    Habis
                                </span>

                            @endif

                        </div>


                        <p class="mt-3 text-2xl font-bold text-orange-600">
                            Rp {{ number_format($menu['harga'], 0, ',', '.') }}
                        </p>


                        <button
                            class="mt-5 w-full rounded-lg bg-orange-600 px-4 py-3 font-semibold text-white transition hover:bg-orange-700">

                            Lihat Detail

                        </button>

                    </div>

                </div>

            @endforeach

        </div>

    @else

        <div class="mt-12 rounded-xl bg-white p-10 text-center shadow-sm">

            <div class="text-5xl">
                🍽️
            </div>

            <h2 class="mt-4 text-xl font-bold">
                Belum Ada Menu
            </h2>

            <p class="mt-2 text-gray-600">
                Saat ini belum tersedia menu makanan.
            </p>

        </div>

    @endif

</section>

@endsection