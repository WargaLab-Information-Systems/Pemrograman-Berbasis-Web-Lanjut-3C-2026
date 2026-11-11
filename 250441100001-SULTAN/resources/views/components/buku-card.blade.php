<div class="card">
    <h3>{{ $judul }}</h3>
    <p><strong>Penulis:</strong> {{ $penulis }}</p>
    <p><strong>Tahun Terbit:</strong> {{ $tahun }}</p>
    
    <div class="kategori-slot">
        {{ $slot }}
    </div>
    
    <a href="{{ $link }}" class="btn">Lihat Detail</a>
</div>