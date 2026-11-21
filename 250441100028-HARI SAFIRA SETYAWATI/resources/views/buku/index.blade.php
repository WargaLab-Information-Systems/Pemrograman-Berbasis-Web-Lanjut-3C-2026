@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<style>
    .library-page {
        max-width: 1200px;
        margin: 35px auto;
        padding: 25px;
        color: #493728;
        font-family: 'Poppins', Arial, sans-serif;
    }

    .library-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
    }

    .library-header h1,
    .form-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #654a32;
    }

    .library-header h1 {
        margin: 0 0 8px;
        font-size: 32px;
    }

    .library-header p {
        margin: 0;
        color: #8b7865;
        font-size: 14px;
    }

    .library-card {
        background: #fffdf9;
        border: 1px solid #eadfce;
        border-radius: 15px;
        padding: 24px;
        margin-bottom: 25px;
        box-shadow: 0 5px 18px rgba(91, 65, 39, 0.06);
    }

    .form-title {
        margin: 0 0 20px;
        font-size: 24px;
    }

    .book-form {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 18px 24px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        min-width: 0;
    }

    .form-group label {
        font-size: 15px;
        font-weight: 600;
        color: #654a32;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        height: 57px;
        box-sizing: border-box;
        padding: 0 16px;
        border: 1px solid #e3cdb5;
        border-radius: 11px;
        background: #fffdf9;
        color: #654a32;
        font-family: 'Poppins', Arial, sans-serif;
        font-size: 15px;
    }

    .form-group input::placeholder {
        color: #8b7865;
        opacity: 1;
    }

    .form-group select {
        cursor: pointer;
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #987955;
        box-shadow: 0 0 0 3px rgba(152, 121, 85, 0.12);
    }

    .form-note,
    .form-actions {
        grid-column: 1 / -1;
    }

    .form-note {
        color: #8b7865;
        font-size: 12px;
        margin: -5px 0 0;
    }

    .form-actions {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .btn {
        display: inline-block;
        border: none;
        border-radius: 9px;
        padding: 11px 16px;
        cursor: pointer;
        font-family: inherit;
        font-size: 13px;
        text-decoration: none;
        text-align: center;
        transition: background 0.2s;
    }

    .btn-primary,
    .btn-refresh,
    .btn-detail,
    .btn-edit {
        background: #806548;
        color: white;
    }

    .btn-primary:hover,
    .btn-refresh:hover,
    .btn-detail:hover,
    .btn-edit:hover {
        background: #654a32;
        color: white;
    }

    .btn-secondary {
        background: #eee2d0;
        color: #654a32;
    }

    .btn-secondary:hover {
        background: #e1d0b9;
    }

    .btn-delete {
        background: #a74e40;
        color: white;
    }

    .btn-delete:hover {
        background: #873b30;
    }

    .btn:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .alert {
        display: none;
        margin-bottom: 18px;
        padding: 12px 15px;
        border-radius: 8px;
        font-size: 13px;
        white-space: pre-line;
        overflow-wrap: anywhere;
    }

    .alert-success {
        display: block;
        background: #edf5e9;
        color: #38643a;
        border: 1px solid #d1e5ca;
    }

    .alert-error {
        display: block;
        background: #fff0ed;
        color: #963d32;
        border: 1px solid #f0d0c9;
    }

    .table-wrapper {
        width: 100%;
        overflow-x: auto;
    }

    .book-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 1000px;
    }

    .book-table th {
        background: #f2e8d9;
        color: #654a32;
        text-align: left;
        padding: 15px 13px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .book-table td {
        padding: 13px;
        border-bottom: 1px solid #eee5d9;
        font-size: 13px;
        color: #5f5043;
        vertical-align: middle;
    }

    .book-table tbody tr:hover {
        background: #faf5ed;
    }

    .category-badge {
        display: inline-block;
        background: #eee2d0;
        color: #765638;
        padding: 6px 10px;
        border-radius: 20px;
        font-size: 12px;
        white-space: nowrap;
    }

    .action-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .action-buttons .btn {
        padding: 8px 10px;
        font-size: 12px;
    }

    .status-message {
        padding: 20px !important;
        text-align: center;
        color: #8b7865 !important;
    }

    @media (max-width: 600px) {
        .library-page {
            padding: 15px;
            margin: 20px auto;
        }

        .library-header h1 {
            font-size: 26px;
        }

        .library-card {
            padding: 15px;
        }

        .book-form {
            grid-template-columns: 1fr;
        }

        .form-note,
        .form-actions {
            grid-column: 1;
        }
    }
</style>

<div class="library-page" data-is-siswa="{{ auth()->check() && auth()->user()->hasRole('siswa') ? 'true' : 'false' }}">
    <div class="library-header">
        <div>
            <h1>Daftar Buku</h1>
            <p>Lihat dan jelajahi koleksi buku perpustakaan.</p>
        </div>

        <button type="button" class="btn btn-refresh" id="btnRefresh">
            ↻ Muat Ulang
        </button>
    </div>

    {{-- Form Tambah dan Ubah Buku HANYA TAMPIL untuk Admin & Petugas --}}
    @hasanyrole('admin|petugas')
    <div class="library-card">
        <h2 class="form-title" id="judulForm">Tambah Buku Baru</h2>

        <div id="pesanForm" class="alert" role="status"></div>

        <form id="formBuku" class="book-form">
            <div class="form-group">
                <label for="kategori_id">Kategori Buku</label>
                <select id="kategori_id" name="kategori_id" required disabled>
                    <option value="">Memuat kategori...</option>
                </select>
            </div>

            <div class="form-group">
                <label for="judul">Judul Buku</label>
                <input
                    type="text"
                    id="judul"
                    name="judul"
                    placeholder="Masukkan judul buku"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="penulis">Penulis</label>
                <input
                    type="text"
                    id="penulis"
                    name="penulis"
                    placeholder="Masukkan nama penulis"
                    maxlength="255"
                    required
                >
            </div>

            <div class="form-group">
                <label for="tahun_terbit">Tahun Terbit</label>
                <input
                    type="number"
                    id="tahun_terbit"
                    name="tahun_terbit"
                    placeholder="Contoh: 2026"
                    min="1000"
                    max="9999"
                    required
                >
            </div>

            <p class="form-note">
                Kategori diambil langsung dari database.
                Pilih kategori yang tersedia sebelum menyimpan buku.
            </p>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary" id="btnSimpan">
                    + Tambah Buku
                </button>

                <button type="reset" class="btn btn-secondary" id="btnKosongkan">
                    Kosongkan Formulir
                </button>
            </div>
        </form>
    </div>
    @endhasanyrole

    {{-- Tabel Koleksi Buku --}}
    <div class="library-card">
        <h2 class="form-title">Koleksi Buku</h2>

        <div id="pesanTabel" class="alert" role="status"></div>

        <div class="table-wrapper">
            <table class="book-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Kategori</th>
                        <th>Judul Buku</th>
                        <th>Penulis</th>
                        <th>Tahun Terbit</th>
                        <th>Aksi</th>
                    </tr>
                </thead>

                <tbody id="daftarBuku">
                    <tr>
                        <td colspan="6" class="status-message">
                            Memuat data buku...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    const libraryPage = document.querySelector('.library-page');
    const isUserSiswa = libraryPage ? libraryPage.dataset.isSiswa === 'true' : false;

    const formBuku = document.getElementById('formBuku');
    const daftarBuku = document.getElementById('daftarBuku');
    const pesanForm = document.getElementById('pesanForm');
    const pesanTabel = document.getElementById('pesanTabel');
    const btnSimpan = document.getElementById('btnSimpan');
    const btnKosongkan = document.getElementById('btnKosongkan');
    const btnRefresh = document.getElementById('btnRefresh');
    const judulForm = document.getElementById('judulForm');
    const pilihanKategori = document.getElementById('kategori_id');

    let idBukuDiubah = null;
    let daftarKategori = [];

    function tampilkanPesan(elemen, pesan, tipe) {
        if (!elemen) return;
        elemen.textContent = pesan;
        elemen.className = 'alert ' +
            (tipe === 'success' ? 'alert-success' : 'alert-error');
    }

    function sembunyikanPesan(elemen) {
        if (!elemen) return;
        elemen.textContent = '';
        elemen.className = 'alert';
    }

    function buatSel(nilai) {
        const td = document.createElement('td');
        td.textContent = nilai ?? '-';
        return td;
    }

    async function bacaRespons(response) {
        const hasil = await response.json().catch(() => ({}));

        if (!response.ok) {
            let pesan;

            if (hasil.errors) {
                const pesanUnik = [...new Set(
                    Object.values(hasil.errors).flat()
                )];

                pesan = pesanUnik.join('\n');
            } else {
                pesan = hasil.message || 'Terjadi kesalahan.';
            }

            throw new Error(pesan);
        }

        return hasil;
    }

    async function muatKategori() {
        if (!pilihanKategori) return;

        pilihanKategori.disabled = true;
        pilihanKategori.replaceChildren();

        const opsiMemuat = document.createElement('option');
        opsiMemuat.value = '';
        opsiMemuat.textContent = 'Memuat kategori...';
        pilihanKategori.appendChild(opsiMemuat);

        const response = await fetch('/api/kategori', {
            headers: {
                'Accept': 'application/json'
            },
            cache: 'no-store'
        });

        const hasil = await bacaRespons(response);

        const kategori = Array.isArray(hasil)
            ? hasil
            : (Array.isArray(hasil.data) ? hasil.data : []);

        daftarKategori = kategori;

        pilihanKategori.replaceChildren();

        const opsiAwal = document.createElement('option');
        opsiAwal.value = '';
        opsiAwal.textContent = kategori.length > 0
            ? '-- Pilih Kategori Buku --'
            : '-- Belum ada kategori di database --';

        pilihanKategori.appendChild(opsiAwal);

        kategori.forEach(item => {
            if (item.id === null || item.id === undefined ||
                !String(item.nama ?? '').trim()) {
                return;
            }

            const opsi = document.createElement('option');
            opsi.value = String(item.id);
            opsi.textContent = String(item.nama).trim();

            pilihanKategori.appendChild(opsi);
        });

        pilihanKategori.disabled = kategori.length === 0;
    }

    async function muatBuku() {
        daftarBuku.replaceChildren();

        const barisMemuat = document.createElement('tr');
        const selMemuat = buatSel('Memuat data buku...');
        selMemuat.colSpan = 6;
        selMemuat.className = 'status-message';
        barisMemuat.appendChild(selMemuat);
        daftarBuku.appendChild(barisMemuat);

        try {
            const response = await fetch('/api/buku', {
                headers: {
                    'Accept': 'application/json'
                },
                cache: 'no-store'
            });

            const hasil = await bacaRespons(response);

            const buku = Array.isArray(hasil)
                ? hasil
                : (Array.isArray(hasil.data) ? hasil.data : []);

            daftarBuku.replaceChildren();

            if (buku.length === 0) {
                const barisKosong = document.createElement('tr');
                const selKosong = buatSel('Belum ada data buku.');
                selKosong.colSpan = 6;
                selKosong.className = 'status-message';
                barisKosong.appendChild(selKosong);
                daftarBuku.appendChild(barisKosong);
                return;
            }

            buku.forEach(item => {
                const tr = document.createElement('tr');

                tr.appendChild(buatSel(item.id));

                const tdKategori = document.createElement('td');
                const badge = document.createElement('span');
                badge.className = 'category-badge';

                const namaKategori =
                    typeof item.kategori === 'object'
                        ? item.kategori?.nama
                        : item.kategori;

                badge.textContent = namaKategori
                    ?? daftarKategori.find(
                        kategori =>
                            Number(kategori.id) === Number(item.kategori_id)
                    )?.nama
                    ?? '-';

                tdKategori.appendChild(badge);
                tr.appendChild(tdKategori);

                tr.appendChild(buatSel(item.judul));
                tr.appendChild(buatSel(item.penulis));
                tr.appendChild(buatSel(item.tahun_terbit ?? item.tahun));

                const tdAksi = document.createElement('td');
                const grup = document.createElement('div');
                grup.className = 'action-buttons';

                // Tombol Detail (Dapat diakses Siswa, Admin, & Guru)
                const btnDetail = document.createElement('button');
                btnDetail.type = 'button';
                btnDetail.className = 'btn btn-detail';
                btnDetail.textContent = 'Detail';
                btnDetail.addEventListener('click', () => {
                    window.location.assign(`/buku/${encodeURIComponent(item.id)}`);
                });
                grup.appendChild(btnDetail);

                // Jika BUKAN Siswa (yaitu Admin/Guru), tampilkan tombol Ubah dan Hapus
                if (!isUserSiswa) {
                    const btnEdit = document.createElement('button');
                    btnEdit.type = 'button';
                    btnEdit.className = 'btn btn-edit';
                    btnEdit.textContent = 'Ubah';
                    btnEdit.addEventListener('click', () => pilihBuku(item));

                    const btnHapus = document.createElement('button');
                    btnHapus.type = 'button';
                    btnHapus.className = 'btn btn-delete';
                    btnHapus.textContent = 'Hapus';
                    btnHapus.addEventListener('click', () => hapusBuku(item));

                    grup.append(btnEdit, btnHapus);
                }

                tdAksi.appendChild(grup);
                tr.appendChild(tdAksi);

                daftarBuku.appendChild(tr);
            });
        } catch (error) {
            const barisError = document.createElement('tr');
            const selError = buatSel(error.message);
            selError.colSpan = 6;
            selError.className = 'status-message';
            barisError.appendChild(selError);
            daftarBuku.replaceChildren(barisError);

            tampilkanPesan(pesanTabel, error.message, 'error');
        }
    }

    function pilihBuku(item) {
        if (!formBuku) return;

        idBukuDiubah = item.id;

        formBuku.elements['kategori_id'].value =
            String(item.kategori_id ?? '');

        formBuku.elements['judul'].value = item.judul ?? '';
        formBuku.elements['penulis'].value = item.penulis ?? '';
        formBuku.elements['tahun_terbit'].value =
            item.tahun_terbit ?? item.tahun ?? '';

        judulForm.textContent = 'Ubah Data Buku';
        btnSimpan.textContent = 'Simpan Perubahan';

        sembunyikanPesan(pesanForm);

        formBuku.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }

    if (formBuku) {
        formBuku.addEventListener('submit', async function(event) {
            event.preventDefault();

            sembunyikanPesan(pesanForm);
            sembunyikanPesan(pesanTabel);

            if (!pilihanKategori.value) {
                tampilkanPesan(
                    pesanForm,
                    'Silakan pilih kategori buku terlebih dahulu.',
                    'error'
                );
                return;
            }

            btnSimpan.disabled = true;
            btnSimpan.textContent = 'Menyimpan...';

            const dataBuku = {
                kategori_id: Number(pilihanKategori.value),
                judul: formBuku.elements['judul'].value.trim(),
                penulis: formBuku.elements['penulis'].value.trim(),
                tahun_terbit: Number(
                    formBuku.elements['tahun_terbit'].value
                )
            };

            const sedangMengubah = idBukuDiubah !== null;

            try {
                const url = sedangMengubah
                    ? `/api/buku/${encodeURIComponent(idBukuDiubah)}`
                    : '/api/buku';

                const response = await fetch(url, {
                    method: sedangMengubah ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(dataBuku)
                });

                await bacaRespons(response);

                formBuku.reset();
                idBukuDiubah = null;
                judulForm.textContent = 'Tambah Buku Baru';

                tampilkanPesan(
                    pesanForm,
                    sedangMengubah
                        ? 'Data buku berhasil diperbarui.'
                        : 'Buku berhasil ditambahkan.',
                    'success'
                );

                await muatBuku();
            } catch (error) {
                tampilkanPesan(pesanForm, error.message, 'error');
            } finally {
                btnSimpan.disabled = false;
                btnSimpan.textContent = idBukuDiubah !== null
                    ? 'Simpan Perubahan'
                    : '+ Tambah Buku';
            }
        });

        btnKosongkan.addEventListener('click', function() {
            idBukuDiubah = null;
            judulForm.textContent = 'Tambah Buku Baru';
            btnSimpan.textContent = '+ Tambah Buku';
            sembunyikanPesan(pesanForm);
        });
    }

    async function hapusBuku(item) {
        const yakin = confirm(
            `Apakah kamu yakin ingin menghapus buku "${item.judul}"?`
        );

        if (!yakin) return;

        sembunyikanPesan(pesanTabel);
        sembunyikanPesan(pesanForm);

        try {
            const response = await fetch(
                `/api/buku/${encodeURIComponent(item.id)}`,
                {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            await bacaRespons(response);

            if (formBuku && String(idBukuDiubah) === String(item.id)) {
                formBuku.reset();
                idBukuDiubah = null;
                judulForm.textContent = 'Tambah Buku Baru';
                btnSimpan.textContent = '+ Tambah Buku';
            }

            tampilkanPesan(
                pesanTabel,
                'Buku berhasil dihapus.',
                'success'
            );

            await muatBuku();
        } catch (error) {
            tampilkanPesan(pesanTabel, error.message, 'error');
        }
    }

    btnRefresh.addEventListener('click', async function() {
        sembunyikanPesan(pesanTabel);
        sembunyikanPesan(pesanForm);

        try {
            await muatKategori();
            await muatBuku();

            tampilkanPesan(
                pesanTabel,
                'Data buku dan kategori berhasil dimuat ulang.',
                'success'
            );
        } catch (error) {
            if (pesanForm) {
                tampilkanPesan(
                    pesanForm,
                    'Gagal memuat kategori: ' + error.message,
                    'error'
                );
            }
        }
    });

    async function inisialisasi() {
        try {
            await muatKategori();
            await muatBuku();
        } catch (error) {
            if (pilihanKategori) {
                pilihanKategori.disabled = true;
                pilihanKategori.replaceChildren();

                const opsiError = document.createElement('option');
                opsiError.value = '';
                opsiError.textContent = 'Gagal memuat kategori';
                pilihanKategori.appendChild(opsiError);
            }

            if (pesanForm) {
                tampilkanPesan(
                    pesanForm,
                    'Gagal memuat kategori dari database. Periksa endpoint /api/kategori. ' +
                        error.message,
                    'error'
                );
            }
        }
    }

    inisialisasi();
</script>
@endsection