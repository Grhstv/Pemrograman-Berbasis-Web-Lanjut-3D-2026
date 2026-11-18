<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Peminjaman & Pengembalian</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <x-flash />
            <table class="w-full text-left border-collapse">
                <thead><tr class="bg-gray-100">
                    <th class="border p-2">Anggota</th><th class="border p-2">Buku</th>
                    <th class="border p-2">Tgl Pinjam</th><th class="border p-2">Tgl Kembali</th>
                    <th class="border p-2">Status</th><th class="border p-2">Aksi</th>
                </tr></thead>
                <tbody>
                @forelse ($peminjamans as $p)
                    <tr>
                        <td class="border p-2">{{ $p->anggota?->nama }}</td>
                        <td class="border p-2">{{ $p->buku?->judul }}</td>
                        <td class="border p-2">{{ $p->tanggal_pinjam }}</td>
                        <td class="border p-2">{{ $p->tanggal_kembali ?? '-' }}</td>
                        <td class="border p-2">{{ ucfirst($p->status) }}</td>
                        <td class="border p-2">
                            @if ($p->status === 'diajukan')
                                @can('memproses persetujuan peminjaman')
                                <form method="POST" action="{{ route('peminjaman.setujui', $p) }}">@csrf
                                    <button class="px-3 py-1 bg-green-600 text-white rounded">Setujui</button></form>
                                @endcan
                            @elseif ($p->status === 'dipinjam')
                                @can('mencatat pengembalian buku')
                                <form method="POST" action="{{ route('peminjaman.kembali', $p) }}">@csrf
                                    <button class="px-3 py-1 bg-blue-600 text-white rounded">Catat Pengembalian</button></form>
                                @endcan
                            @else - @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="border p-2 text-center">Belum ada data.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
