<x-app-layout>
    <x-slot name="header"><h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $buku->exists ? 'Ubah Buku' : 'Tambah Buku' }}</h2></x-slot>
    <div class="py-12"><div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white shadow-sm sm:rounded-lg p-6">
            <x-flash />
            <form method="POST" action="{{ $buku->exists ? route('buku.update', $buku) : route('buku.store') }}">
                @csrf
                @if ($buku->exists) @method('PUT') @endif

                <label class="block mb-1">Judul</label>
                <input name="judul" value="{{ old('judul', $buku->judul) }}" class="w-full border rounded p-2 mb-3" required>

                <label class="block mb-1">Penulis</label>
                <input name="penulis" value="{{ old('penulis', $buku->penulis) }}" class="w-full border rounded p-2 mb-3" required>

                <label class="block mb-1">Tahun Terbit</label>
                <input type="number" name="tahun_terbit" value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" class="w-full border rounded p-2 mb-3" required>

                <label class="block mb-1">Kategori</label>
                <select name="kategori_id" class="w-full border rounded p-2 mb-4" required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach ($kategoris as $k)
                        <option value="{{ $k->id }}" @selected(old('kategori_id', $buku->kategori_id) == $k->id)>{{ $k->nama }}</option>
                    @endforeach
                </select>

                <button class="px-4 py-2 bg-blue-600 text-white rounded">Simpan</button>
                <a href="{{ route('buku.daftar') }}" class="ml-3 underline">Batal</a>
            </form>
        </div>
    </div></div>
</x-app-layout>
