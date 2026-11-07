@extends('layouts.app')

@section('title', 'Kelola Buku - KATAMITHA')

@section('content')

    <div class="book-list-page">

        {{-- =====================================================
             JUDUL HALAMAN
             ===================================================== --}}
        <div class="section-heading">

            <span>KELOLA KOLEKSI</span>

            <h2>Kelola Buku</h2>

            <p>
                Kelola koleksi buku perpustakaan digital KATAMITHA.
                Tambahkan buku baru, perbarui data buku, atau hapus
                buku yang sudah tidak diperlukan.
            </p>

            <div class="book-list-meta">

                <div>
                    <strong id="jumlah-buku">0</strong>
                    <span>TOTAL BUKU</span>
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
             FORM TAMBAH DAN UBAH BUKU
             ===================================================== --}}
        @can('kelola-buku')
        <div class="book-form-section" id="bagian-form">
        <div class="book-form-section" id="bagian-form">

            <div class="section-heading">

                <span>DATA BUKU</span>

                <h2 id="judul-form">
                    Tambah Buku
                </h2>

                <p id="deskripsi-form">
                    Tambahkan koleksi buku baru ke dalam perpustakaan
                    digital KATAMITHA melalui form berikut.
                </p>

            </div>


            <div id="pesan-form"></div>


            <form
                id="form-buku"
                enctype="multipart/form-data"
                novalidate
            >

                <input type="hidden" id="buku-id">


                <div class="form-group">

                    <label for="kategori_id">
                        Kategori
                    </label>

                    <select
                        id="kategori_id"
                        name="kategori_id"
                    >

                        <option value="">
                            Pilih Kategori
                        </option>

                        @foreach ($daftarKategori as $itemKategori)

                            <option value="{{ $itemKategori->id }}">
                                {{ $itemKategori->nama }}
                            </option>

                        @endforeach

                    </select>

                </div>


                <div class="form-group">

                    <label for="judul">
                        Judul Buku
                    </label>

                    <input
                        type="text"
                        id="judul"
                        name="judul"
                        placeholder="Masukkan judul buku"
                    >

                </div>


                <div class="form-group">

                    <label for="penulis">
                        Penulis
                    </label>

                    <input
                        type="text"
                        id="penulis"
                        name="penulis"
                        placeholder="Masukkan nama penulis"
                    >

                </div>


                <div class="form-group">

                    <label for="tahun_terbit">
                        Tahun Terbit
                    </label>

                    <input
                        type="number"
                        id="tahun_terbit"
                        name="tahun_terbit"
                        min="1900"
                        max="{{ date('Y') }}"
                        placeholder="Masukkan tahun terbit"
                    >

                </div>


                <div class="form-group form-group-full">

                    <label for="image">
                        Cover Buku
                        <span class="optional-label">
                            Opsional
                        </span>
                    </label>

                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                    >

                    <small class="form-help">
                        Format JPG, JPEG, atau PNG. Maksimal 2 MB.
                    </small>


                    <div
                        id="cover-lama-info"
                        class="cover-old-info"
                        style="display: none;"
                    >
                        Cover saat ini tetap digunakan jika kamu tidak
                        memilih file baru.
                    </div>


                    <div
                        id="cover-preview-wrapper"
                        class="cover-preview-wrapper"
                        style="display: none;"
                    >

                        <span>PREVIEW COVER</span>

                        <img
                            id="cover-preview"
                            src=""
                            alt="Preview cover buku"
                        >

                    </div>

                </div>


                <div class="form-group form-group-full">

                    <label for="description">
                        Deskripsi
                        <span class="optional-label">
                            Opsional
                        </span>
                    </label>

                    <textarea
                        id="description"
                        name="description"
                        rows="5"
                        placeholder="Masukkan deskripsi singkat buku jika tersedia"
                    ></textarea>

                </div>


                <div class="form-actions">

                    <button
                        type="button"
                        id="tombol-batal"
                        class="cancel-button"
                        style="display: none;"
                    >
                        Batal
                    </button>

                    <button
                        type="submit"
                        id="tombol-simpan"
                        class="detail-button"
                    >
                        Simpan Buku
                    </button>

                </div>

            </form>

        </div>
        @endcan

        {{-- =====================================================
             TABEL DATA BUKU
             ===================================================== --}}
        <div class="book-table-section">

            <div class="section-heading">

                <span>DATA RESTFUL API</span>

                <h2>Data Buku</h2>

                <p>
                    Data buku berikut diambil dari database melalui
                    RESTful API KATAMITHA.
                </p>

            </div>


            <div id="pesan-api"></div>


            <div class="book-table-wrapper">

                <table class="book-table">

                    <thead>

                        <tr>
                            <th>No.</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Tahun Terbit</th>
                            <th>Aksi</th>
                        </tr>

                    </thead>

                    <tbody id="book-table-body">

                        {{-- Data diisi melalui Fetch API --}}

                    </tbody>

                </table>

            </div>

        </div>

    </div>

        {{-- Form penghapusan buku dengan CSRF protection --}}
    <form
        id="form-hapus-buku"
        method="POST"
        style="display: none;"
    >
        @csrf
        @method('DELETE')
    </form>

@endsection


@push('scripts')

<script>

    const bolehKelolaBuku = @json(auth()->user()->can('kelola-buku'));

    let modeEdit = false;


    // =========================================================
    // CSRF TOKEN
    // =========================================================

    const csrfToken = document
        .querySelector('meta[name="csrf-token"]')
        .getAttribute('content');


    // =========================================================
    // MENGAMBIL SELURUH DATA BUKU DARI API
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

                semuaBuku = semuaBuku.concat(result.data);

            }


            if (result.meta && result.meta.last_page) {

                halamanTerakhir = result.meta.last_page;

            }


            halaman++;

        } while (halaman <= halamanTerakhir);


        return semuaBuku;
    }


    // =========================================================
    // MENAMPILKAN DATA BUKU PADA TABEL
    // =========================================================

    async function loadBuku() {

        const bookTableBody =
            document.getElementById('book-table-body');

        const jumlahBuku =
            document.getElementById('jumlah-buku');

        const pesanApi =
            document.getElementById('pesan-api');


        try {

            const buku = await ambilSemuaBuku();


            bookTableBody.innerHTML = '';
            pesanApi.innerHTML = '';

            jumlahBuku.textContent = buku.length;


            if (buku.length === 0) {

                bookTableBody.innerHTML = `
                    <tr>
                        <td
                            colspan="6"
                            class="table-empty-message"
                        >
                            Belum ada buku yang tersedia.
                        </td>
                    </tr>
                `;

                return;
            }


            buku.forEach(function (item, index) {

                const row =
                    document.createElement('tr');


                row.innerHTML = `

                    <td class="table-number">
                        ${index + 1}
                    </td>

                    <td>
                        <span class="table-category">
                            ${escapeHtml(
                                item.kategori ?? 'Tanpa Kategori'
                            )}
                        </span>
                    </td>

                    <td class="table-title">
                        ${escapeHtml(item.judul_buku)}
                    </td>

                    <td>
                        ${escapeHtml(item.penulis)}
                    </td>

                    <td class="table-year">
                        ${escapeHtml(item.tahun)}
                    </td>

                    <td>
                        ${
                            bolehKelolaBuku
                                ? `
                                    <div class="table-actions">

                                        <button
                                            type="button"
                                            class="table-edit-button"
                                            data-id="${escapeHtml(item.id)}"
                                        >
                                            Ubah
                                        </button>

                                        <button
                                            type="button"
                                            class="table-delete-button"
                                            data-id="${escapeHtml(item.id)}"
                                            data-judul="${escapeHtml(item.judul_buku)}"
                                        >
                                            Hapus
                                        </button>

                                    </div>
                                `
                                : ''
                        }
                  </td>

                `;


                bookTableBody.appendChild(row);

            });


            document
                .querySelectorAll('.table-edit-button')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            mulaiUbahBuku(
                                this.dataset.id
                            );

                        }
                    );

                });


            document
                .querySelectorAll('.table-delete-button')
                .forEach(function (button) {

                    button.addEventListener(
                        'click',
                        function () {

                            hapusBuku(
                                this.dataset.id,
                                this.dataset.judul
                            );

                        }
                    );

                });


        } catch (error) {

            console.error(error);

            jumlahBuku.textContent = '0';

            bookTableBody.innerHTML = `
                <tr>
                    <td
                        colspan="6"
                        class="table-empty-message"
                    >
                        Data buku gagal dimuat.
                    </td>
                </tr>
            `;

            pesanApi.innerHTML = `
                <p class="error-message">
                    Data buku gagal dimuat. Silakan coba kembali.
                </p>
            `;

        }

    }


    // =========================================================
    // PREVIEW COVER
    // =========================================================

    function tampilkanPreviewCover() {

        const inputImage =
            document.getElementById('image');

        const wrapper =
            document.getElementById(
                'cover-preview-wrapper'
            );

        const preview =
            document.getElementById(
                'cover-preview'
            );


        if (
            !inputImage.files ||
            inputImage.files.length === 0
        ) {

            wrapper.style.display = 'none';

            preview.src = '';

            return;

        }


        const file = inputImage.files[0];


        const tipeDiizinkan = [
            'image/jpeg',
            'image/png'
        ];


        if (!tipeDiizinkan.includes(file.type)) {

            inputImage.value = '';

            wrapper.style.display = 'none';

            preview.src = '';

            tampilkanPesanError(
                'Cover harus berupa file JPG, JPEG, atau PNG.'
            );

            return;

        }


        if (file.size > 2 * 1024 * 1024) {

            inputImage.value = '';

            wrapper.style.display = 'none';

            preview.src = '';

            tampilkanPesanError(
                'Ukuran cover maksimal 2 MB.'
            );

            return;

        }


        const reader = new FileReader();


        reader.onload = function (event) {

            preview.src = event.target.result;

            wrapper.style.display = 'flex';

        };


        reader.readAsDataURL(file);

    }


    // =========================================================
    // MEMULAI MODE UBAH
    // =========================================================

    async function mulaiUbahBuku(id) {

        const pesanForm =
            document.getElementById('pesan-form');


        try {

            pesanForm.innerHTML = '';


            const response = await fetch(
                '/api/buku/' + encodeURIComponent(id),
                {
                    method: 'GET',

                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );


            const result = await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Data buku gagal diambil.'
                );

            }


            const buku = result.data;


            if (!buku) {

                throw new Error(
                    'Data buku tidak ditemukan.'
                );

            }


            modeEdit = true;


            document.getElementById(
                'buku-id'
            ).value = buku.id;

            document.getElementById(
                'kategori_id'
            ).value = buku.kategori_id;

            document.getElementById(
                'judul'
            ).value = buku.judul_buku;

            document.getElementById(
                'penulis'
            ).value = buku.penulis;

            document.getElementById(
                'tahun_terbit'
            ).value = buku.tahun;

            document.getElementById(
                'description'
            ).value = buku.description ?? '';

            document.getElementById(
                'image'
            ).value = '';


            document.getElementById(
                'cover-preview-wrapper'
            ).style.display = 'none';

            document.getElementById(
                'cover-preview'
            ).src = '';


            const coverLamaInfo =
                document.getElementById(
                    'cover-lama-info'
                );


            if (buku.image) {

                coverLamaInfo.innerHTML = `
                    Cover saat ini:
                    <strong>
                        ${escapeHtml(buku.image)}
                    </strong>.
                    Jika tidak memilih file baru,
                    cover ini tetap digunakan.
                `;

            } else {

                coverLamaInfo.innerHTML =
                    'Buku ini belum memiliki cover. ' +
                    'Kamu boleh menambahkan cover baru.';

            }


            coverLamaInfo.style.display = 'block';


            document.getElementById(
                'judul-form'
            ).textContent = 'Ubah Buku';


            document.getElementById(
                'deskripsi-form'
            ).textContent =
                'Perbarui data buku yang dipilih. ' +
                'Cover baru dan deskripsi tetap bersifat opsional.';


            document.getElementById(
                'tombol-simpan'
            ).textContent = 'Simpan Perubahan';


            document.getElementById(
                'tombol-batal'
            ).style.display = 'inline-flex';


            document.getElementById(
                'bagian-form'
            ).scrollIntoView({

                behavior: 'smooth',
                block: 'start'

            });


        } catch (error) {

            console.error(error);

            pesanForm.innerHTML = `
                <p class="error-message">
                    ${escapeHtml(error.message)}
                </p>
            `;

        }

    }


    // =========================================================
    // MENYIMPAN BUKU
    // POST = TAMBAH
    // POST + _method PUT = UBAH
    // =========================================================

    async function simpanBuku(event) {

        event.preventDefault();


        const formBuku =
            document.getElementById('form-buku');

        const tombolSimpan =
            document.getElementById(
                'tombol-simpan'
            );

        const pesanForm =
            document.getElementById(
                'pesan-form'
            );

        const bukuId =
            document.getElementById(
                'buku-id'
            ).value;


        const formData = new FormData();


        formData.append(
            'kategori_id',
            document.getElementById(
                'kategori_id'
            ).value
        );

        formData.append(
            'judul',
            document.getElementById(
                'judul'
            ).value.trim()
        );

        formData.append(
            'penulis',
            document.getElementById(
                'penulis'
            ).value.trim()
        );

        formData.append(
            'tahun_terbit',
            document.getElementById(
                'tahun_terbit'
            ).value
        );


        const description =
            document.getElementById(
                'description'
            ).value.trim();

        formData.append(
            'description',
            description
        );


        const inputImage =
            document.getElementById(
                'image'
            );


        if (
            inputImage.files &&
            inputImage.files.length > 0
        ) {

            formData.append(
                'image',
                inputImage.files[0]
            );

        }


        if (modeEdit) {

            formData.append(
                '_method',
                'PUT'
            );

        }


        pesanForm.innerHTML = '';

        tombolSimpan.disabled = true;

        tombolSimpan.textContent =
            'Menyimpan...';


        try {

            const url = modeEdit
                ? '/api/buku/' +
                    encodeURIComponent(bukuId)
                : '/api/buku';


            const response = await fetch(
                url,
                {
                    method: 'POST',

                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },

                    body: formData
                }
            );


            const result =
                await response.json();


            if (!response.ok) {

                if (
                    response.status === 422 &&
                    result.errors
                ) {

                    const daftarError =
                        Object
                            .values(result.errors)
                            .flat()
                            .map(function (pesan) {

                                return escapeHtml(
                                    pesan
                                );

                            })
                            .join('<br>');


                    throw new Error(
                        daftarError
                    );

                }


                throw new Error(
                    result.message ||
                    (
                        modeEdit
                            ? 'Buku gagal diubah.'
                            : 'Buku gagal ditambahkan.'
                    )
                );

            }


            const prosesEdit = modeEdit;


            formBuku.reset();

            document.getElementById(
                'buku-id'
            ).value = '';


            modeEdit = false;

            resetTampilanForm();


            await loadBuku();


            pesanForm.innerHTML =
                prosesEdit
                    ? `
                        <p class="success-message">
                            Buku berhasil diubah.
                        </p>
                    `
                    : `
                        <p class="success-message">
                            Buku berhasil ditambahkan.
                        </p>
                    `;


        } catch (error) {

            console.error(error);

            pesanForm.innerHTML = `
                <p class="error-message">
                    ${escapeHtml(error.message)}
                </p>
            `;


        } finally {

            tombolSimpan.disabled = false;


            if (modeEdit) {

                tombolSimpan.textContent =
                    'Simpan Perubahan';

            } else {

                tombolSimpan.textContent =
                    'Simpan Buku';

            }

        }

    }


    // =========================================================
    // MENGHAPUS BUKU DENGAN FORM DELETE DAN CSRF
    // =========================================================

    async function hapusBuku(id, judul) {

        const konfirmasi = confirm(
            'Apakah kamu yakin ingin menghapus buku "' +
            judul +
            '"?'
        );


        if (!konfirmasi) {

            return;

        }


        const pesanApi =
            document.getElementById('pesan-api');

        const formHapus =
            document.getElementById('form-hapus-buku');


        pesanApi.innerHTML = '';


        try {

            // Menentukan alamat penghapusan buku
            formHapus.action =
                '/buku/' + encodeURIComponent(id);


            // Mengambil token CSRF dan method DELETE dari form
            const formData = new FormData(formHapus);


            // Mengirim permintaan penghapusan
            const response = await fetch(
                formHapus.action,
                {
                    method: 'POST',

                    headers: {
                        'Accept': 'application/json'
                    },

                    body: formData
                }
            );


            const result = await response.json();


            if (!response.ok) {

                throw new Error(
                    result.message ||
                    'Buku gagal dihapus.'
                );

            }


            const bukuSedangDiedit =
                document.getElementById('buku-id').value;


            if (
                modeEdit &&
                String(bukuSedangDiedit) === String(id)
            ) {

                document.getElementById(
                    'form-buku'
                ).reset();

                document.getElementById(
                    'buku-id'
                ).value = '';

                modeEdit = false;

                resetTampilanForm();

            }


            await loadBuku();


            pesanApi.innerHTML = `
                <p class="success-message">
                    Buku "${escapeHtml(judul)}"
                    berhasil dihapus.
                </p>
            `;


        } catch (error) {

            console.error(error);

            pesanApi.innerHTML = `
                <p class="error-message">
                    ${escapeHtml(error.message)}
                </p>
            `;

        }

    }

    // =========================================================
    // BATAL UBAH
    // =========================================================

    function batalUbah() {

        document.getElementById(
            'form-buku'
        ).reset();

        document.getElementById(
            'buku-id'
        ).value = '';

        document.getElementById(
            'pesan-form'
        ).innerHTML = '';

        modeEdit = false;

        resetTampilanForm();

    }


    // =========================================================
    // RESET TAMPILAN FORM
    // =========================================================

    function resetTampilanForm() {

        document.getElementById(
            'judul-form'
        ).textContent = 'Tambah Buku';


        document.getElementById(
            'deskripsi-form'
        ).textContent =
            'Tambahkan koleksi buku baru ke dalam ' +
            'perpustakaan digital KATAMITHA melalui form berikut.';


        document.getElementById(
            'tombol-simpan'
        ).textContent = 'Simpan Buku';


        document.getElementById(
            'tombol-batal'
        ).style.display = 'none';


        document.getElementById(
            'cover-lama-info'
        ).style.display = 'none';


        document.getElementById(
            'cover-preview-wrapper'
        ).style.display = 'none';


        document.getElementById(
            'cover-preview'
        ).src = '';

    }


    // =========================================================
    // MENAMPILKAN PESAN ERROR
    // =========================================================

    function tampilkanPesanError(pesan) {

        document.getElementById(
            'pesan-form'
        ).innerHTML = `
            <p class="error-message">
                ${escapeHtml(pesan)}
            </p>
        `;

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


            document
                .getElementById('form-buku')
                .addEventListener(
                    'submit',
                    simpanBuku
                );


            document
                .getElementById('tombol-batal')
                .addEventListener(
                    'click',
                    batalUbah
                );


            document
                .getElementById('image')
                .addEventListener(
                    'change',
                    tampilkanPreviewCover
                );

        }
    );

</script>

@endpush