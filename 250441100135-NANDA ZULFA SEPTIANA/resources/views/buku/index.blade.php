@extends('layouts.app')

@section('title', 'Manajemen Buku - Perpustakaan')

@section('content')
<div class="page-header">
    <div>
        <span class="page-label">MANAJEMEN KOLEKSI</span>
        <h2>Data Buku</h2>
        <p>Tambah, ubah, dan hapus koleksi buku perpustakaan.</p>
    </div>
</div>

<div id="pesan" role="status" aria-live="polite"></div>

<section class="form-buku">
    <h3 id="judul-form">Tambah Buku</h3>

    <form id="form-buku">
        <div class="form-group">
           <select id="kategori_id" name="kategori_id" required>
    <option value="">-- Pilih Kategori --</option>
    <option value="1">Novel</option>
    <option value="2">Pendidikan</option>
    <option value="3">Komik</option>
</select>
        </div>

        <div class="form-group">
            <label for="judul">Judul Buku</label>
            <input type="text" id="judul" name="judul"
                   placeholder="Masukkan judul buku" required>
        </div>

        <div class="form-group">
            <label for="penulis">Penulis</label>
            <input type="text" id="penulis" name="penulis"
                   placeholder="Masukkan nama penulis" required>
        </div>

        <div class="form-group">
            <label for="tahun_terbit">Tahun Terbit</label>
            <input type="number" id="tahun_terbit" name="tahun_terbit"
                   min="1000" max="2100"
                   placeholder="Contoh: 2024" required>
        </div>

        <button type="submit" id="tombol-simpan" class="btn btn-primary">
            Simpan Buku
        </button>

        <button type="button" id="tombol-batal"
                class="btn btn-detail" hidden>
            Batal Ubah
        </button>
    </form>
</section>

<section class="tabel-buku">
    <h3>Daftar Buku</h3>

    <div class="table-responsive">
        <table class="tabel-data">
            <thead>
                <tr>
                    <th>Kategori</th>
                    <th>Judul</th>
                    <th>Penulis</th>
                    <th>Tahun Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="daftar-buku">
                <tr>
                    <td colspan="5">Memuat data buku...</td>
                </tr>
            </tbody>
        </table>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const API_URL = '/api/buku';

    const form = document.getElementById('form-buku');
    const daftarBuku = document.getElementById('daftar-buku');
    const pesan = document.getElementById('pesan');
    const judulForm = document.getElementById('judul-form');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const tombolBatal = document.getElementById('tombol-batal');

    let idEdit = null;

    function tampilkanPesan(teks, tipe = 'success') {
        pesan.textContent = teks;
        pesan.className = tipe === 'error' ? 'pesan-error' : 'pesan-sukses';
    }

    function kosongkanForm() {
        form.reset();
        idEdit = null;
        judulForm.textContent = 'Tambah Buku';
        tombolSimpan.textContent = 'Simpan Buku';
        tombolBatal.hidden = true;
    }

    async function bacaRespons(response) {
        const data = await response.json().catch(() => ({}));

        if (!response.ok) {
            const detailValidasi = data.errors
                ? Object.values(data.errors).flat().join(' ')
                : '';

            throw new Error(
                detailValidasi ||
                data.message ||
                `Permintaan gagal (HTTP ${response.status}).`
            );
        }

        return data;
    }

    async function muatBuku() {
        daftarBuku.replaceChildren();

        const barisMemuat = document.createElement('tr');
        const selMemuat = document.createElement('td');
        selMemuat.colSpan = 5;
        selMemuat.textContent = 'Memuat data buku...';
        barisMemuat.appendChild(selMemuat);
        daftarBuku.appendChild(barisMemuat);

        try {
            const response = await fetch(API_URL, {
                headers: { 'Accept': 'application/json' }
            });

            const hasil = await bacaRespons(response);
            const buku = hasil.data ?? hasil;

            daftarBuku.replaceChildren();

            if (!Array.isArray(buku) || buku.length === 0) {
                const baris = document.createElement('tr');
                const sel = document.createElement('td');
                sel.colSpan = 5;
                sel.textContent = 'Belum ada data buku.';
                baris.appendChild(sel);
                daftarBuku.appendChild(baris);
                return;
            }

            buku.forEach(item => {
                const baris = document.createElement('tr');

                [item.kategori, item.judul, item.penulis,
                 item.tahun_terbit].forEach(nilai => {
                    const sel = document.createElement('td');
                    sel.textContent = nilai ?? '-';
                    baris.appendChild(sel);
                });

                const selAksi = document.createElement('td');

                const tombolUbah = document.createElement('button');
                tombolUbah.type = 'button';
                tombolUbah.textContent = 'Ubah';
                tombolUbah.className = 'btn btn-primary';
                tombolUbah.addEventListener('click', () => mulaiEdit(item));

                const tombolHapus = document.createElement('button');
                tombolHapus.type = 'button';
                tombolHapus.textContent = 'Hapus';
                tombolHapus.className = 'btn btn-hapus';
                tombolHapus.addEventListener('click', () => hapusBuku(item));

                selAksi.append(tombolUbah, document.createTextNode(' '), tombolHapus);
                baris.appendChild(selAksi);
                daftarBuku.appendChild(baris);
            });
        } catch (error) {
            daftarBuku.replaceChildren();
            tampilkanPesan('Gagal mengambil data buku: ' + error.message, 'error');

            const baris = document.createElement('tr');
            const sel = document.createElement('td');
            sel.colSpan = 5;
            sel.textContent = 'Data tidak dapat dimuat. Periksa koneksi API.';
            baris.appendChild(sel);
            daftarBuku.appendChild(baris);
        }
    }

    function mulaiEdit(buku) {
        idEdit = buku.id;

document.getElementById('kategori_id').value =
    buku.kategori_id ?? '';
        document.getElementById('judul').value = buku.judul ?? '';
        document.getElementById('penulis').value = buku.penulis ?? '';
        document.getElementById('tahun_terbit').value =
            buku.tahun_terbit ?? '';

        judulForm.textContent = 'Ubah Buku';
        tombolSimpan.textContent = 'Simpan Perubahan';
        tombolBatal.hidden = false;

        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    form.addEventListener('submit', async event => {
        event.preventDefault();

        const dataBuku = {
    kategori_id: Number(
        document.getElementById('kategori_id').value
    ),
    judul_buku: document.getElementById('judul').value.trim(),
    penulis: document.getElementById('penulis').value.trim(),
    tahun_terbit: Number(
        document.getElementById('tahun_terbit').value
    )
};

        const sedangEdit = idEdit !== null;
        const url = sedangEdit
            ? `${API_URL}/${idEdit}`
            : API_URL;

        tombolSimpan.disabled = true;
        tampilkanPesan('Menyimpan data...');

        try {
            const response = await fetch(url, {
                method: sedangEdit ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(dataBuku)
            });

            await bacaRespons(response);

            kosongkanForm();
            await muatBuku();

            tampilkanPesan(
                sedangEdit
                    ? 'Data buku berhasil diubah.'
                    : 'Buku berhasil ditambahkan.'
            );
        } catch (error) {
            tampilkanPesan(
                'Gagal menyimpan buku: ' + error.message,
                'error'
            );
        } finally {
            tombolSimpan.disabled = false;
        }
    });

    tombolBatal.addEventListener('click', () => {
        kosongkanForm();
        tampilkanPesan('Perubahan dibatalkan.');
    });

    async function hapusBuku(buku) {
        if (!confirm(`Yakin ingin menghapus buku "${buku.judul}"?`)) {
            return;
        }

        try {
            const response = await fetch(`${API_URL}/${buku.id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json' }
            });

            await bacaRespons(response);

            if (idEdit === buku.id) {
                kosongkanForm();
            }

            await muatBuku();
            tampilkanPesan('Buku berhasil dihapus.');
        } catch (error) {
            tampilkanPesan(
                'Gagal menghapus buku: ' + error.message,
                'error'
            );
        }
    }

    muatBuku();
});
</script>
@endsection