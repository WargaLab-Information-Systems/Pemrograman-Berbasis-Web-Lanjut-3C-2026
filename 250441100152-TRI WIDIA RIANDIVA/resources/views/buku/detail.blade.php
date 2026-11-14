<x-app-layout>
<div class="container bagian-buku">
    <h2 class="judul" style="font-size: 1.8rem; margin-bottom: 20px; font-weight: 900;">Detail Data Buku</h2>

    <div class="form-container-buku" style="width: 100%; max-width: 100%; margin: 0;">
        @php
            $judul = is_array($buku) ? ($buku['judul'] ?? '-') : ($buku->judul ?? '-');
            $penulis = is_array($buku) ? ($buku['penulis'] ?? '-') : ($buku->penulis ?? '-');
            $tahun = is_array($buku) ? ($buku['tahun_terbit'] ?? '-') : ($buku->tahun_terbit ?? '-');
            $kategori = is_array($buku) 
                ? (is_array($buku['kategori'] ?? null) ? ($buku['kategori']['nama'] ?? '-') : ($buku['kategori'] ?? '-'))
                : ($buku->kategori->nama ?? $buku->kategori ?? '-');
        @endphp

        @if($buku)
            <div style="margin-bottom: 20px;">
                <h3 style="font-size: 1.4rem; font-weight: 700; color: #0f172a; margin-bottom: 15px;">{{ $judul }}</h3>
                <p style="margin-bottom: 8px;"><strong>Penulis:</strong> {{ $penulis }}</p>
                <p style="margin-bottom: 8px;"><strong>Tahun Terbit:</strong> {{ $tahun }}</p>
                <p style="margin-bottom: 8px;"><strong>Kategori:</strong> {{ $kategori }}</p>
            </div>
        @else
            <p style="color: #ef4444; font-weight: bold;">Data buku tidak ditemukan.</p>
        @endif

        <div class="form-aksi" style="margin-top: 20px;">
            <a href="{{ route('buku.index') }}" class="btn btn-kembali" style="text-decoration: none; display: inline-flex;">
                Kembali ke Daftar Buku
            </a>
        </div>
    </div>
</div>
</x-app-layout>