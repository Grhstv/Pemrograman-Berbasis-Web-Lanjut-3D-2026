<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Riwayat Peminjaman — {{ $pemilik->name }}</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <x-flash />
            <table class="w-full text-left border-collapse">
                <thead><tr class="bg-gray-100">
                    <th class="border p-2">Buku</th><th class="border p-2">Tgl Pinjam</th>
                    <th class="border p-2">Tgl Kembali</th><th class="border p-2">Status</th>
                </tr></thead>
                <tbody>
                @forelse ($riwayat as $r)
                    <tr>
                        <td class="border p-2">{{ $r->buku?->judul }}</td>
                        <td class="border p-2">{{ $r->tanggal_pinjam }}</td>
                        <td class="border p-2">{{ $r->tanggal_kembali ?? '-' }}</td>
                        <td class="border p-2">{{ ucfirst($r->status) }}</td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="border p-2 text-center">Belum ada riwayat peminjaman.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
