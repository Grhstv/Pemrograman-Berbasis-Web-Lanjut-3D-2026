<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">Daftar Buku (render Blade)</h2></x-slot>
    <div class="py-12"><div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <x-flash />
            @can('kelola-buku')
                <a href="{{ route('buku.create') }}" class="inline-block mb-4 px-4 py-2 bg-blue-600 text-white rounded">Tambah Buku</a>
            @endcan
            <table class="w-full text-left border-collapse">
                <thead><tr class="bg-gray-100">
                    <th class="border p-2">Kategori</th><th class="border p-2">Judul</th>
                    <th class="border p-2">Penulis</th><th class="border p-2">Tahun</th>
                    @can('kelola-buku')<th class="border p-2">Aksi</th>@endcan
                </tr></thead>
                <tbody>
                @forelse ($bukus as $b)
                    <tr>
                        <td class="border p-2">{{ $b->kategori?->nama }}</td>
                        <td class="border p-2">{{ $b->judul }}</td>
                        <td class="border p-2">{{ $b->penulis }}</td>
                        <td class="border p-2">{{ $b->tahun_terbit }}</td>
                        @can('kelola-buku')
                        <td class="border p-2">
                            <div class="flex gap-2">
                                <a href="{{ route('buku.edit', $b) }}" class="px-3 py-1 bg-yellow-500 text-white rounded">Ubah</a>
                                <form method="POST" action="{{ route('buku.destroy', $b) }}" onsubmit="return confirm('Hapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="px-3 py-1 bg-red-600 text-white rounded">Hapus</button>
                                </form>
                            </div>
                        </td>
                        @endcan
                    </tr>
                @empty
                    <tr><td colspan="5" class="border p-2 text-center">Belum ada buku.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div></div>
</x-app-layout>
