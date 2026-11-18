@props(['id', 'judul', 'penulis', 'tahun'])

<div class="card-buku">
    <div class="card-body">
        <h3 class="card-title">{{ $judul }}</h3>
        <p class="card-text"><strong>Penulis:</strong> {{ $penulis }}</p>
        <p class="card-text"><strong>Tahun Terbit:</strong> {{ $tahun }}</p>
        
        <!-- Slot Tambahan (Misal: Kategori / Label) -->
        @if (isset($extra))
            <div class="card-extra">
                {{ $extra }}
            </div>
        @endif
    </div>
    
    <div class="card-footer">
        <a href="{{ route('buku.show', $id) }}" class="btn btn-primary">Lihat Detail</a>
    </div>
</div>