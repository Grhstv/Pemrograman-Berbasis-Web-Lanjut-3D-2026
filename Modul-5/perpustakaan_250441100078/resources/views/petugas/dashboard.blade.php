<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Dashboard Petugas</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <p class="mb-4">Selamat datang, {{ auth()->user()->name }} (Petugas).</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Total Buku</div><div class="text-2xl font-bold">{{ $totalBuku }}</div></div>
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Menunggu Persetujuan</div><div class="text-2xl font-bold">{{ $menunggu }}</div></div>
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Sedang Dipinjam</div><div class="text-2xl font-bold">{{ $sedangDipinjam }}</div></div>
            </div>
        </div>
    </div></div>
</x-app-layout>
