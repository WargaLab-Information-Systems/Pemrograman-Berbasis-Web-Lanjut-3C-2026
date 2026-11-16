<div class="card">
    <div class="card-content">
        <h3>{{ $buku['judul'] }}</h3>
        <p><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
        <p><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>

        @if (isset($extra))
            <div class="extra-info">
                {{ $extra }}
            </div>
        @endif
    </div>

    <a href="{{ route('buku.show', $buku['id']) }}" class="btn">Lihat Detail Buku</a>
</div>  

 