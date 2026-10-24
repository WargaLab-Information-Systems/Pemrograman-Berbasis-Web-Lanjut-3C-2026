<div class="card">

    <h3>{{ $buku['judul'] }}</h3>

    <p>
        <strong>Penulis:</strong>
        {{ $buku['penulis'] }}
    </p>

    <p>
        <strong>Tahun:</strong>
        {{ $buku['tahun'] }}
    </p>

    {{ $slot }}

</div>