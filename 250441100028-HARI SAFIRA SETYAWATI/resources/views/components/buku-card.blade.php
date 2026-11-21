@props([
    'judul',
    'penulis',
    'tahun',
    'kategori',
    'id'
])

<div class="book-card">

    <span class="category">
        {{ $kategori }}
    </span>

    <h3>
        {{ $judul }}
    </h3>

    <p>
        Penulis: {{ $penulis }}
    </p>

    <p>
        Tahun: {{ $tahun }}
    </p>

    {{ $slot }}

</div>

