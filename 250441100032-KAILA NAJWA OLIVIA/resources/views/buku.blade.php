
@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div style="max-width:1200px; width:100%; margin:0 auto; box-sizing:border-box; padding:0 16px;">

    <h2 class="judul">Daftar Buku</h2>

    <div style="margin-top:20px; padding:24px; background:white; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.06); box-sizing:border-box;">
        <h3 id="judul-form" style="font-size:22px; font-weight:bold; margin-bottom:20px;">
            Tambah Buku
        </h3>

        <form id="form-buku">
            <input type="hidden" id="buku_id">

            <div style="margin-bottom:16px;">
                <label for="kategori_id" style="display:block; margin-bottom:6px;">ID Kategori</label>
                <input
                    type="number"
                    id="kategori_id"
                    required
                    min="1"
                    max="5"
                    step="1"
                    placeholder="Masukkan ID kategori"
                    style="display:block; width:100%; box-sizing:border-box; padding:12px; border:1px solid #ccc; border-radius:7px;"
                >
                <p id="peringatan-kategori" role="alert" style="color:red; margin-top:6px;"></p>
                
            </div>

            <div style="margin-bottom:16px;">
                <label for="judul" style="display:block; margin-bottom:6px;">Judul Buku</label>
                <input
                    type="text"
                    id="judul"
                    required
                    minlength="3"
                    maxlength="150"
                    placeholder="Masukkan judul buku"
                    style="display:block; width:100%; box-sizing:border-box; padding:12px; border:1px solid #ccc; border-radius:7px;"
                >
            </div>

            <div style="margin-bottom:16px;">
                <label for="penulis" style="display:block; margin-bottom:6px;">Penulis</label>
                <input
                    type="text"
                    id="penulis"
                    required
                    minlength="3"
                    maxlength="100"
                    placeholder="Masukkan nama penulis"
                    style="display:block; width:100%; box-sizing:border-box; padding:12px; border:1px solid #ccc; border-radius:7px;"
                >
            </div>

            <div style="margin-bottom:20px;">
                <label for="tahun_terbit" style="display:block; margin-bottom:6px;">Tahun Terbit</label>
                <input
                    type="number"
                    id="tahun_terbit"
                    required
                    min="1900"
                    max="{{ date('Y') }}"
                    placeholder="Contoh: 2024"
                    style="display:block; width:100%; box-sizing:border-box; padding:12px; border:1px solid #ccc; border-radius:7px;"
                >
            </div>

            <button
                type="submit"
                id="tombol-simpan"
                style="background:#0f766e; color:white; padding:11px 22px; border:none; border-radius:7px; cursor:pointer;"
            >Tambah Buku</button>

            <button
                type="button"
                id="tombol-batal"
                hidden
                style="background:#6b7280; color:white; padding:11px 22px; border:none; border-radius:7px; cursor:pointer; margin-left:6px;"
            >Batal Ubah</button>
        </form>

        <p id="pesan-form" role="status" aria-live="polite" style="margin-top:12px;"></p>
    </div>

    <div style="overflow-x:auto; margin-top:24px; border-radius:10px; background:white; box-shadow:0 2px 8px rgba(0,0,0,0.06);">
        <table style="width:100%; min-width:800px; table-layout:fixed; border-collapse:collapse; background:white;">
            <thead>
                <tr style="background:#0f766e; color:white;">
                    <th style="width:12%; padding:14px 8px;">Kode Kategori</th>
                    <th style="width:18%; padding:14px 8px;">Kategori</th>
                    <th style="width:23%; padding:14px 10px; text-align:left;">Judul</th>
                    <th style="width:19%; padding:14px 10px; text-align:left;">Penulis</th>
                    <th style="width:13%; padding:14px 8px;">Tahun Terbit</th>
                    <th style="width:15%; padding:14px 8px;">Aksi</th>
                </tr>
            </thead>
            <tbody id="daftar-buku">
                <tr>
                    <td colspan="6" style="padding:18px; text-align:center;">Memuat data buku...</td>
                </tr>
            </tbody>
        </table>
    </div>

    <p id="pesan-error" role="alert" style="color:red; margin-top:15px;"></p>
</div>

<script>
    const formBuku = document.getElementById('form-buku');
    const tabel = document.getElementById('daftar-buku');
    const pesanForm = document.getElementById('pesan-form');
    const pesanError = document.getElementById('pesan-error');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const tombolBatal = document.getElementById('tombol-batal');
    const judulForm = document.getElementById('judul-form');
    const inputKategori = document.getElementById('kategori_id');
    const peringatanKategori = document.getElementById('peringatan-kategori');

    const namaKategori = {
        1: 'Novel',
        2: 'Fantasi',
        3: 'Biografi',
        4: 'Pendidikan',
        5: 'Pengembangan Diri'
    };

    function periksaKategori() {
        const kode = inputKategori.value.trim();
        const angka = Number(kode);

        if (
            kode === '' ||
            !Number.isInteger(angka) ||
            angka < 1 ||
            angka > 5 ||
            !namaKategori[angka]
        ) {
            peringatanKategori.textContent = 'Kategori tidak valid!';
            return false;
        }

        peringatanKategori.textContent = '';
        return true;
    }

    inputKategori.addEventListener('input', periksaKategori);

    async function tampilkanBuku() {
        pesanError.textContent = '';

        try {
            const response = await fetch('/api/buku', {
                method: 'GET',
                headers: { 'Accept': 'application/json' },
                cache: 'no-store'
            });

            const hasil = await response.json();

            if (!response.ok) {
                throw new Error(hasil.message || 'Gagal mengambil data buku.');
            }

            if (!Array.isArray(hasil.data)) {
                throw new Error('Format data buku tidak sesuai.');
            }

            tabel.innerHTML = '';

            if (hasil.data.length === 0) {
                tabel.innerHTML = `
                    <tr>
                        <td colspan="6" style="padding:18px;text-align:center;">
                            Belum ada data buku.
                        </td>
                    </tr>`;
                return true;
            }

            hasil.data.forEach(item => {
                const baris = document.createElement('tr');

                const kategoriId = item.kategori_id ?? item.kategori?.id ?? '';
                const kategoriNama = item.kategori?.nama
                    ?? item.kategori
                    ?? namaKategori[kategoriId]
                    ?? '-';

                [
                    kategoriId || '-',
                    kategoriNama,
                    item.judul,
                    item.penulis,
                    item.tahun_terbit
                ].forEach((nilai, index) => {
                    const kolom = document.createElement('td');

                    kolom.style.cssText = `
                        padding:14px 10px;
                        border-bottom:1px solid #e5e7eb;
                        vertical-align:middle;
                        overflow-wrap:anywhere;
                        text-align:${index === 0 || index === 4 ? 'center' : 'left'};
                    `;

                    kolom.textContent = nilai ?? '-';
                    baris.appendChild(kolom);
                });

                const kolomAksi = document.createElement('td');
                kolomAksi.style.cssText = `
                    padding:10px 6px;
                    border-bottom:1px solid #e5e7eb;
                    vertical-align:middle;
                `;

                // Tombol dibuat berjajar ke samping
                const wadahTombol = document.createElement('div');
                wadahTombol.style.cssText = `
                    display:flex;
                    flex-direction:row;
                    justify-content:center;
                    align-items:center;
                    gap:6px;
                    width:100%;
                `;

                const tombolUbah = document.createElement('button');
                tombolUbah.type = 'button';
                tombolUbah.textContent = 'Ubah';
                tombolUbah.style.cssText = `
                    background:#2563eb;
                    color:white;
                    padding:7px 9px;
                    border:none;
                    border-radius:6px;
                    cursor:pointer;
                    white-space:nowrap;
                    font-size:12px;
                `;

                tombolUbah.addEventListener('click', () => mulaiUbah(item));

                const tombolHapus = document.createElement('button');
                tombolHapus.type = 'button';
                tombolHapus.textContent = 'Hapus';
                tombolHapus.style.cssText = `
                    background:#dc2626;
                    color:white;
                    padding:7px 9px;
                    border:none;
                    border-radius:6px;
                    cursor:pointer;
                    white-space:nowrap;
                    font-size:12px;
                `;

                tombolHapus.addEventListener('click', async () => {
                    if (!confirm(`Yakin ingin menghapus buku "${item.judul}"?`)) {
                        return;
                    }

                    tombolHapus.disabled = true;

                    try {
                        const response = await fetch(`/api/buku/${item.id}`, {
                            method: 'DELETE',
                            headers: { 'Accept': 'application/json' }
                        });

                        const hasil = await response.json();

                        if (!response.ok) {
                            throw new Error(hasil.message || 'Buku gagal dihapus.');
                        }

                        await tampilkanBuku();
                        pesanForm.textContent = hasil.message || 'Buku berhasil dihapus.';
                        pesanForm.style.color = 'green';
                    } catch (error) {
                        pesanForm.textContent = 'Gagal: ' + error.message;
                        pesanForm.style.color = 'red';
                    } finally {
                        tombolHapus.disabled = false;
                    }
                });

                wadahTombol.appendChild(tombolUbah);
                wadahTombol.appendChild(tombolHapus);
                kolomAksi.appendChild(wadahTombol);
                baris.appendChild(kolomAksi);
                tabel.appendChild(baris);
            });

            return true;
        } catch (error) {
            tabel.innerHTML = `
                <tr>
                    <td colspan="6" style="padding:18px;text-align:center;">
                        Data buku gagal dimuat.
                    </td>
                </tr>`;

            pesanError.textContent = 'Gagal memuat buku: ' + error.message;
            return false;
        }
    }

    function mulaiUbah(item) {
        document.getElementById('buku_id').value = item.id;
        inputKategori.value = item.kategori_id ?? item.kategori?.id ?? '';
        peringatanKategori.textContent = '';

        document.getElementById('judul').value = item.judul ?? '';
        document.getElementById('penulis').value = item.penulis ?? '';
        document.getElementById('tahun_terbit').value = item.tahun_terbit ?? '';

        judulForm.textContent = 'Ubah Buku';
        tombolSimpan.textContent = 'Simpan Perubahan';
        tombolBatal.hidden = false;

        pesanForm.textContent = 'Silakan ubah data buku yang dipilih.';
        pesanForm.style.color = '#2563eb';

        formBuku.scrollIntoView({ behavior: 'smooth' });
    }

    formBuku.addEventListener('submit', async function(event) {
        event.preventDefault();

        pesanForm.textContent = '';
        pesanError.textContent = '';

        if (!periksaKategori()) {
            inputKategori.focus();
            return;
        }

        const id = document.getElementById('buku_id').value;
        const sedangUbah = id !== '';

        const dataBuku = {
            kategori_id: Number(inputKategori.value),
            judul: document.getElementById('judul').value.trim(),
            penulis: document.getElementById('penulis').value.trim(),
            tahun_terbit: Number(document.getElementById('tahun_terbit').value)
        };

        tombolSimpan.disabled = true;
        tombolSimpan.textContent = sedangUbah ? 'Menyimpan...' : 'Menambahkan...';

        try {
            const response = await fetch(
                sedangUbah ? `/api/buku/${id}` : '/api/buku',
                {
                    method: sedangUbah ? 'PUT' : 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(dataBuku)
                }
            );

            const hasil = await response.json();

            if (!response.ok) {
                const detailError = hasil.errors
                    ? Object.values(hasil.errors).flat().join(' ')
                    : hasil.message;

                throw new Error(detailError || 'Permintaan gagal diproses.');
            }

            const berhasilMemuat = await tampilkanBuku();
            resetForm();

            pesanForm.textContent = berhasilMemuat
                ? (hasil.message || 'Data buku berhasil disimpan.')
                : 'Data tersimpan, tetapi daftar gagal diperbarui. Muat ulang halaman.';

            pesanForm.style.color = berhasilMemuat ? 'green' : '#b45309';
        } catch (error) {
            pesanForm.textContent = 'Gagal: ' + error.message;
            pesanForm.style.color = 'red';
        } finally {
            tombolSimpan.disabled = false;
            tombolSimpan.textContent = 'Tambah Buku';
        }
    });

    tombolBatal.addEventListener('click', resetForm);

    function resetForm() {
        formBuku.reset();
        document.getElementById('buku_id').value = '';
        peringatanKategori.textContent = '';
        judulForm.textContent = 'Tambah Buku';
        tombolSimpan.textContent = 'Tambah Buku';
        tombolBatal.hidden = true;
        pesanForm.textContent = '';
    }

    tampilkanBuku();
</script>
@endsection