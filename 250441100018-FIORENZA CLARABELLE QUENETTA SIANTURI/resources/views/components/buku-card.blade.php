<div class="book-card">

    <div class="book-icon">
        
    </div>

    <div class="book-content">

        <h3>{{ $judul }}</h3>

        <p>
            <strong>Penulis:</strong>
            {{ $penulis }}
        </p>

        <p>
            <strong>Tahun:</strong>
            {{ $tahun }}
        </p>
 
        <div class="book-extra">
            {{ $slot }}
        </div>

    </div>

</div>