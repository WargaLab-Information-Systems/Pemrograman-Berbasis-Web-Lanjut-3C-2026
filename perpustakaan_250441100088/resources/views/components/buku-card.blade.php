@props(['nomor' => null, 'judul', 'penulis', 'tahun', 'kategori', 'url', 'sampul' => null])

@php
    $adaSampul = $sampul && file_exists(public_path('images/' . $sampul));
@endphp

<article class="buku-card" data-kategori="{{ $kategori }}">
    @if ($adaSampul)
        <a href="{{ $url }}" class="card-cover">
            <img src="{{ asset('images/' . $sampul) }}" alt="Sampul buku {{ $judul }}" loading="lazy">
        </a>
    @endif

    <div class="card-top">
        {{-- named slot: label kategori --}}
        <span class="kategori">{{ $label ?? $kategori }}</span>

        @if ($nomor)
            <span class="card-no">{{ str_pad($nomor, 2, '0', STR_PAD_LEFT) }}</span>
        @endif
    </div>

    <div class="card-body">
        <h3>{{ $judul }}</h3>
        <p class="penulis">{{ $penulis }}</p>
        <p class="tahun">Terbit {{ $tahun }}</p>

        {{-- default slot: keterangan tambahan (opsional) --}}
        @if ($slot->isNotEmpty())
            <div class="card-extra">{{ $slot }}</div>
        @endif
    </div>

    <div class="card-action">
        <a href="{{ $url }}" class="detail-btn">
            Lihat detail <span aria-hidden="true">→</span>
        </a>
    </div>
</article>
