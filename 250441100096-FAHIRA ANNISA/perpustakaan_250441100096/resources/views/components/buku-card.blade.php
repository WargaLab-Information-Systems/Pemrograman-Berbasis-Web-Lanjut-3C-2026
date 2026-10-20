@props(['buku'])
    <div class="card-buku">
        {{-- Gambar Sampul Buku --}}
        <img src="{{ asset($buku['gambar']) }}" alt="{{
        $buku['judul'] }}" class="card-buku-img">
    <div class="card-buku-body">
    <div>
        {{-- Badge Kategori --}}
        {{ $badge ?? '' }}
        <h3>{{ $buku['judul'] }}</h3>
        <p class="card-info-text"><strong>ID Buku:</strong> <code class="id-buku">{{ $buku['id']}}</code></p>
        <p class="card-info-text"><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
        <p class="card-info-text"><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>
    </div>
        
        {{-- Tombol Detail --}}
        <a href="{{ route('buku.show', $buku['id']) }}" class="btn-detail">Lihat Detail</a>
    </div>
</div>