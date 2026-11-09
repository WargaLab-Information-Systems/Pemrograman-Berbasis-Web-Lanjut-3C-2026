
@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

<div class="page-title">
    <h2>Daftar Koleksi Buku</h2>
    <p>Berikut adalah koleksi buku yang tersedia di perpustakaan.</p>
</div>

<style>
    .book-form {
        margin-bottom: 25px;
        padding: 20px;
        background: #fff7fb;
        border: 1px solid #ead7e5;
        border-radius: 12px;
    }

    .book-form label {
        display: block;
        margin-top: 12px;
        margin-bottom: 5px;
        font-weight: 600;
    }

    .book-form input,
    .book-form select {
        width: 100%;
        padding: 10px;
        border: 1px solid #d8c5d3;
        border-radius: 7px;
        box-sizing: border-box;
    }

    .book-form button,
    .book-table button {
        padding: 8px 12px;
        margin-top: 12px;
        margin-right: 5px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    .btn-save {
        background: #a65c89;
        color: white;
    }

    .btn-cancel {
        background: #e7dce4;
        color: #333;
    }

    .btn-edit {
        background: #f1d9a7;
        color: #333;
    }

    .btn-delete {
        background: #e8b8c0;
        color: #333;
    }

    .book-table-wrapper {
        overflow-x: auto;
    }

    .book-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .book-table th,
    .book-table td {
        border: 1px solid #eadde6;
        padding: 10px;
        text-align: left;
    }

    .book-table th {
        background: #f4e5ef;
    }

    .book-message {
        margin-top: 10px;
        color: #a12645;
    }

    .book-pagination {
        margin-top: 15px;
    }

    .book-pagination button {
        padding: 8px 12px;
        margin-right: 5px;
        cursor: pointer;
    }
</style>

<div class="book-form">
    <h3 id="judulForm">Tambah Buku</h3>

    <form id="formBuku">
        @csrf

        <input type="hidden" id="buku_id">

        <label for="kategori_id">Kategori</label>
        <select id="kategori_id" name="kategori_id" required>
            <option value="">Pilih kategori</option>

            @foreach ($kategoris as $kategori)
                <option value="{{ $kategori->id }}">
                    {{ $kategori->nama }}
                </option>
            @endforeach
        </select>

        <label for="judul">Judul Buku</label>
        <input
            type="text"
            id="judul"
            name="judul"
            minlength="3"
            maxlength="150"
            required
        >

        <label for="penulis">Penulis</label>
        <input
            type="text"
            id="penulis"
            name="penulis"
            minlength="3"
            maxlength="100"
            required
        >

        <label for="tahun_terbit">Tahun Terbit</label>
        <input
            type="number"
            id="tahun_terbit"
            name="tahun_terbit"
            min="1900"
            max="{{ date('Y') }}"
            required
        >

        <button type="submit" class="btn-save" id="btnSimpan">
            Tambah Buku
        </button>

        <button
            type="button"
            class="btn-cancel"
            id="btnBatal"
            onclick="resetForm()"
            hidden
        >
            Batal
        </button>

        <p id="pesanForm" class="book-message" role="alert"></p>
    </form>
</div>

<h3>Daftar Koleksi Buku</h3>

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

        <tbody id="tbodyBuku">
            <tr>
                <td colspan="6">Memuat data buku...</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="book-pagination">
    <button type="button" id="btnPrev" onclick="halamanSebelumnya()">
        Sebelumnya
    </button>

    <span id="infoHalaman">Halaman 1</span>

    <button type="button" id="btnNext" onclick="halamanBerikutnya()">
        Berikutnya
    </button>
</div>

<script>
    const API_URL = '/api/buku';

    const formBuku = document.getElementById('formBuku');
    const tbodyBuku = document.getElementById('tbodyBuku');
    const pesanForm = document.getElementById('pesanForm');

    let halamanAktif = 1;
    let halamanTerakhir = 1;

    async function bacaRespons(response) {
        let hasil;

        try {
            hasil = await response.json();
        } catch (error) {
            throw new Error('Respons server bukan JSON yang valid.');
        }

        if (!response.ok) {
            if (hasil.errors) {
                const daftarError = Object.values(hasil.errors)
                    .flat()
                    .join('\n');

                throw new Error(
                    daftarError || hasil.message || 'Permintaan gagal.'
                );
            }

            throw new Error(
                hasil.message || `Permintaan gagal (${response.status}).`
            );
        }

        return hasil;
    }

    function ambilDaftarBuku(hasil) {
        let bagian = hasil;

        for (let i = 0; i < 5; i++) {
            if (Array.isArray(bagian)) {
                return {
                    bukus: bagian,
                    meta: null
                };
            }

            if (!bagian || typeof bagian !== 'object') {
                break;
            }

            if (Array.isArray(bagian.data)) {
                return {
                    bukus: bagian.data,
                    meta: bagian.meta ?? null
                };
            }

            bagian = bagian.data;
        }

        return {
            bukus: [],
            meta: null
        };
    }

    async function muatBuku() {
        tbodyBuku.innerHTML = `
            <tr>
                <td colspan="6">Memuat data buku...</td>
            </tr>
        `;

        try {
            const response = await fetch(
                `${API_URL}?page=${halamanAktif}`,
                {
                    headers: {
                        'Accept': 'application/json'
                    }
                }
            );

            const hasil = await bacaRespons(response);
            const daftar = ambilDaftarBuku(hasil);
            const bukus = daftar.bukus;
            const meta = daftar.meta;

            halamanAktif = meta?.current_page ?? 1;
            halamanTerakhir = meta?.last_page ?? 1;

            tbodyBuku.innerHTML = '';

            if (bukus.length === 0) {
                tbodyBuku.innerHTML = `
                    <tr>
                        <td colspan="6">Belum ada data buku.</td>
                    </tr>
                `;
                perbaruiPagination();
                return;
            }

            bukus.forEach((buku, index) => {
                const baris = document.createElement('tr');

                const nomor = meta?.from
                    ? meta.from + index
                    : (halamanAktif - 1) * 10 + index + 1;

                const nilai = [
                    nomor,
                    buku.kategori ?? '-',
                    buku.judul ?? '-',
                    buku.penulis ?? '-',
                    buku.tahunTerbit ?? buku.tahun_terbit ?? '-'
                ];

                nilai.forEach((isi) => {
                    const kolom = document.createElement('td');
                    kolom.textContent = isi;
                    baris.appendChild(kolom);
                });

                const kolomAksi = document.createElement('td');

                const tombolUbah = document.createElement('button');
                tombolUbah.type = 'button';
                tombolUbah.className = 'btn-edit';
                tombolUbah.textContent = 'Ubah';
                tombolUbah.addEventListener('click', () => {
                    isiForm(buku.id);
                });

                const tombolHapus = document.createElement('button');
                tombolHapus.type = 'button';
                tombolHapus.className = 'btn-delete';
                tombolHapus.textContent = 'Hapus';
                tombolHapus.addEventListener('click', () => {
                    hapusBuku(buku.id, buku.judul);
                });

                kolomAksi.append(tombolUbah, tombolHapus);
                baris.appendChild(kolomAksi);
                tbodyBuku.appendChild(baris);
            });

            perbaruiPagination();

        } catch (error) {
            console.error('Gagal memuat buku:', error);

            tbodyBuku.innerHTML = `
                <tr>
                    <td colspan="6">Data buku gagal dimuat.</td>
                </tr>
            `;
        }
    }

    function perbaruiPagination() {
        document.getElementById('infoHalaman').textContent =
            `Halaman ${halamanAktif} dari ${halamanTerakhir}`;

        document.getElementById('btnPrev').disabled =
            halamanAktif <= 1;

        document.getElementById('btnNext').disabled =
            halamanAktif >= halamanTerakhir;
    }

    function halamanSebelumnya() {
        if (halamanAktif > 1) {
            halamanAktif--;
            muatBuku();
        }
    }

    function halamanBerikutnya() {
        if (halamanAktif < halamanTerakhir) {
            halamanAktif++;
            muatBuku();
        }
    }

    function resetForm() {
        formBuku.reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('judulForm').textContent = 'Tambah Buku';
        document.getElementById('btnSimpan').textContent = 'Tambah Buku';
        document.getElementById('btnBatal').hidden = true;
        pesanForm.textContent = '';
    }

    async function isiForm(id) {
        pesanForm.textContent = '';

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                headers: {
                    'Accept': 'application/json'
                }
            });

            const hasil = await bacaRespons(response);

            let detail = hasil.data;

            for (let i = 0; i < 4; i++) {
                if (
                    detail &&
                    !Array.isArray(detail) &&
                    detail.id !== undefined
                ) {
                    break;
                }

                detail = detail?.data;
            }

            if (!detail || detail.id === undefined) {
                throw new Error('Detail buku tidak ditemukan.');
            }

            document.getElementById('buku_id').value = detail.id;
            document.getElementById('judul').value = detail.judul ?? '';
            document.getElementById('penulis').value = detail.penulis ?? '';
            document.getElementById('tahun_terbit').value =
                detail.tahunTerbit ?? detail.tahun_terbit ?? '';

            const pilihanKategori =
                document.getElementById('kategori_id');

            if (detail.kategori_id !== undefined) {
                pilihanKategori.value = detail.kategori_id;
            } else {
                const opsi = Array.from(pilihanKategori.options).find(
                    option => option.textContent.trim() === detail.kategori
                );

                pilihanKategori.value = opsi ? opsi.value : '';
            }

            document.getElementById('judulForm').textContent = 'Ubah Buku';
            document.getElementById('btnSimpan').textContent = 'Simpan Perubahan';
            document.getElementById('btnBatal').hidden = false;

            document.getElementById('judulForm').scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });

        } catch (error) {
            console.error('Gagal mengambil detail buku:', error);
            pesanForm.textContent = error.message;
        }
    }

    formBuku.addEventListener('submit', async function (event) {
        event.preventDefault();

        pesanForm.textContent = '';

        const id = document.getElementById('buku_id').value;

        const dataBuku = {
            kategori_id: document.getElementById('kategori_id').value,
            judul: document.getElementById('judul').value.trim(),
            penulis: document.getElementById('penulis').value.trim(),
            tahun_terbit: Number(
                document.getElementById('tahun_terbit').value
            )
        };

        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

        try {
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataBuku)
            });

            const hasil = await bacaRespons(response);

            pesanForm.textContent =
                hasil.message || 'Data buku berhasil disimpan.';

            resetForm();

            halamanAktif = 1;
            await muatBuku();

        } catch (error) {
            console.error('Gagal menyimpan buku:', error);
            pesanForm.textContent = error.message;
        }
    });

    async function hapusBuku(id, judul) {
        const setuju = confirm(
            `Apakah kamu yakin ingin menghapus buku "${judul}"?`
        );

        if (!setuju) {
            return;
        }

        pesanForm.textContent = '';

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json'
                }
            });

            const hasil = await bacaRespons(response);

            pesanForm.textContent =
                hasil.message || 'Buku berhasil dihapus.';

            await muatBuku();

        } catch (error) {
            console.error('Gagal menghapus buku:', error);
            pesanForm.textContent = error.message;
        }
    }

    muatBuku();
</script>

@endsection
