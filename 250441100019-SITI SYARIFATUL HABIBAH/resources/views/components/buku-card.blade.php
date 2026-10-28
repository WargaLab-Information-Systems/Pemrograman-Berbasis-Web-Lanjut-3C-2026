<div class="book-card">

    <h3>
        {{ $judul }}
    </h3>


    <p>
        <strong>Penulis:</strong>
        {{ $penulis }}
    </p>


    <p>
        <strong>Tahun Terbit:</strong>
        {{ $tahun }}
    </p>


    <div class="book-card-footer">

        {{ $footer }}


        <a
            href="{{ route('buku.show', $id) }}"
            class="button"
        >
            Lihat Detail
        </a>

    </div>

</div>

