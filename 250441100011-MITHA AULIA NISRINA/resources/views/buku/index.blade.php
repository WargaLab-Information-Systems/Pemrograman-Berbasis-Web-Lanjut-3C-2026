@extends('layouts.app')

@section('title', $kategori ? $kategori . ' - KATAMITHA' : 'Daftar Buku - KATAMITHA')

@section('content')

    <div class="book-list-page">

        <div class="section-heading">

            @if ($kategori)

                <span>KOLEKSI KATAMITHA</span>

                <h2>{{ $kategori }}</h2>

                <p>
                    Berikut adalah koleksi buku dalam kategori
                    {{ $kategori }} yang tersedia di KATAMITHA.
                    Temukan bacaan yang sesuai dengan minatmu dan
                    nikmati berbagai cerita, pengetahuan, serta
                    perspektif dari setiap halaman.
                </p>

            @else

                <span>KOLEKSI KATAMITHA</span>

                <h2>Daftar Buku</h2>

                <p>
                    Berikut adalah koleksi buku yang tersedia di KATAMITHA.
                    Jelajahi berbagai bacaan dari beragam kategori dan
                    temukan cerita, pengetahuan, serta perspektif baru
                    yang dapat menemani perjalanan membacamu.
                </p>

            @endif


            <div class="book-list-meta">

                <div>
                    <strong id="jumlah-buku">0</strong>
                    <span>BUKU TERSEDIA</span>
                </div>

                <div>
                    <strong>KATAMITHA</strong>
                    <span>PERPUSTAKAAN DIGITAL</span>
                </div>

                <div>
                    <strong>6</strong>
                    <span>KATEGORI BACAAN</span>
                </div>

            </div>

        </div>


        {{-- =====================================================
             TAMPILAN KOLEKSI BUKU
             ===================================================== --}}

        <div class="book-grid" id="book-grid">

            {{-- Data buku diisi melalui Fetch API --}}

        </div>

    </div>

@endsection


@push('scripts')

<script>

    const kategoriAktif = @json($kategori);


    // =========================================================
    // MENGAMBIL SELURUH DATA BUKU DARI RESTFUL API
    // =========================================================

    async function ambilSemuaBuku() {

        let semuaBuku = [];
        let halaman = 1;
        let halamanTerakhir = 1;


        do {

            const response = await fetch(
                '/api/buku?page=' + halaman,
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );


            if (!response.ok) {

                throw new Error(
                    'Gagal mengambil data buku. Status HTTP: ' +
                    response.status
                );

            }


            const result = await response.json();


            if (Array.isArray(result.data)) {

                semuaBuku = semuaBuku.concat(
                    result.data
                );

            }


            if (
                result.meta &&
                result.meta.last_page
            ) {

                halamanTerakhir =
                    result.meta.last_page;

            }


            halaman++;

        } while (halaman <= halamanTerakhir);


        return semuaBuku;

    }


    // =========================================================
    // MENAMPILKAN KOLEKSI BUKU
    // =========================================================

    async function loadBuku() {

        const bookGrid =
            document.getElementById('book-grid');

        const jumlahBuku =
            document.getElementById('jumlah-buku');


        try {

            let buku =
                await ambilSemuaBuku();


            /*
             * Jika pengguna membuka koleksi berdasarkan
             * kategori, data difilter sesuai kategori aktif.
             */
            if (kategoriAktif) {

                buku = buku.filter(
                    function (item) {

                        return (
                            item.kategori ===
                            kategoriAktif
                        );

                    }
                );

            }


            bookGrid.innerHTML = '';

            jumlahBuku.textContent =
                buku.length;


            /*
             * Jika tidak ada buku pada kategori yang dipilih.
             */
            if (buku.length === 0) {

                bookGrid.innerHTML = `
                    <p class="empty-book-message">
                        Belum ada buku yang tersedia.
                    </p>
                `;

                return;

            }


            /*
             * Membuat card untuk setiap buku.
             */
            buku.forEach(
                function (item) {

                    const card =
                        document.createElement('div');

                    card.className =
                        'book-card';


                    /*
                     * Jika buku memiliki cover,
                     * tampilkan gambar cover.
                     *
                     * Jika cover kosong,
                     * gunakan placeholder KATAMITHA.
                     */
                    const coverBuku =
                        item.image
                            ? `
                                <img
                                    src="/images/books/${encodeURIComponent(item.image)}"
                                    alt="Cover ${escapeHtml(item.judul_buku)}"
                                >
                            `
                            : `
                                <div class="book-cover-placeholder">

                                    <span>
                                        KATAMITHA
                                    </span>

                                    <strong>
                                        ${escapeHtml(item.judul_buku)}
                                    </strong>

                                </div>
                            `;


                    card.innerHTML = `

                        <div class="book-cover">

                            <span class="book-category">
                                ${escapeHtml(
                                    item.kategori ??
                                    'Tanpa Kategori'
                                )}
                            </span>

                            ${coverBuku}

                        </div>


                        <div class="book-content">

                            <span class="book-year">
                                ${escapeHtml(item.tahun)}
                            </span>

                            <h3>
                                ${escapeHtml(
                                    item.judul_buku
                                )}
                            </h3>

                            <p class="book-author">
                                ${escapeHtml(
                                    item.penulis
                                )}
                            </p>


                            <div class="book-card-actions">

                                <a
                                    href="/buku/${encodeURIComponent(item.id)}"
                                    class="detail-button"
                                >
                                    Lihat Detail
                                    <span>→</span>
                                </a>

                            </div>

                        </div>

                    `;


                    bookGrid.appendChild(
                        card
                    );

                }
            );


        } catch (error) {

            console.error(error);


            jumlahBuku.textContent =
                '0';


            bookGrid.innerHTML = `
                <p class="empty-book-message">
                    Data buku gagal dimuat.
                    Silakan coba kembali.
                </p>
            `;

        }

    }


    // =========================================================
    // ESCAPE HTML
    // =========================================================

    function escapeHtml(value) {

        if (
            value === null ||
            value === undefined
        ) {

            return '';

        }


        return String(value)
            .replaceAll('&', '&amp;')
            .replaceAll('<', '&lt;')
            .replaceAll('>', '&gt;')
            .replaceAll('"', '&quot;')
            .replaceAll("'", '&#039;');

    }


    // =========================================================
    // MENJALANKAN PROGRAM
    // =========================================================

    document.addEventListener(
        'DOMContentLoaded',
        function () {

            loadBuku();

        }
    );

</script>

@endpush