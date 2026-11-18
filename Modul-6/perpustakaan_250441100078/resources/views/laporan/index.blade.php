<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Laporan Perpustakaan</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Total Buku</div><div class="text-2xl font-bold">{{ $totalBuku }}</div></div>
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Diajukan</div><div class="text-2xl font-bold">{{ $diajukan }}</div></div>
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Dipinjam</div><div class="text-2xl font-bold">{{ $dipinjam }}</div></div>
                <div class="bg-gray-50 border rounded p-4"><div class="text-sm text-gray-500">Dikembalikan</div><div class="text-2xl font-bold">{{ $dikembalikan }}</div></div>
            </div>
            <h3 class="font-semibold mb-2">10 Transaksi Terbaru</h3>
            <table class="w-full text-left border-collapse">
                <thead><tr class="bg-gray-100"><th class="border p-2">Anggota</th><th class="border p-2">Buku</th><th class="border p-2">Status</th></tr></thead>
                <tbody>
                @foreach ($terbaru as $t)
                    <tr><td class="border p-2">{{ $t->anggota?->nama }}</td><td class="border p-2">{{ $t->buku?->judul }}</td><td class="border p-2">{{ ucfirst($t->status) }}</td></tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
