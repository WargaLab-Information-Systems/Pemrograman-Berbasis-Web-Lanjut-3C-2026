@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

        <!-- Header Halaman -->
        <div class="page-heading">
            <h2 class="text-2xl font-bold text-gray-900">Daftar Data Buku</h2>
            <p class="text-gray-600 text-sm mt-1">Total <span id="total-buku-count">0</span> buku tersedia di perpustakaan.</p>
        </div>

        {{-- Kotak Pesan Sukses / Error --}}
        <div id="alert-box" style="display: none; padding: 12px; border-radius: 4px;"></div>

        {{-- Formulir Tambah & Ubah Buku (Khusus Admin & Petugas) --}}
        @hasanyrole('admin|petugas')
        <div class="card-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 id="form-title" style="margin-bottom: 16px; color: #2c3e50; font-size: 1.25rem; font-weight: 700;">Tambah Buku Baru</h3>

            <form id="buku-form">
                <input type="hidden" id="buku_id">

                <!-- Dropdown Kategori Render Langsung dari Blade -->
                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="kategori_id" style="display: block; font-weight: 600; margin-bottom: 6px;">Kategori</label>
                    <select id="kategori_id" class="form-control" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
                        <option value="">-- Pilih Kategori --</option>
                        @forelse($kategoris as $kat)
                            <option value="{{ $kat->id }}">{{ $kat->nama_kategori ?? $kat->nama }}</option>
                        @empty
                            <option value="">(Belum Ada Data Kategori di Database)</option>
                        @endforelse
                    </select>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="judul" style="display: block; font-weight: 600; margin-bottom: 6px;">Judul Buku</label>
                    <input type="text" id="judul" class="form-control" placeholder="Masukkan judul buku" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="penulis" style="display: block; font-weight: 600; margin-bottom: 6px;">Penulis</label>
                    <input type="text" id="penulis" class="form-control" placeholder="Masukkan nama penulis" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>

                <div class="form-group" style="margin-bottom: 14px;">
                    <label for="tahun_terbit" style="display: block; font-weight: 600; margin-bottom: 6px;">Tahun Terbit</label>
                    <input type="number" id="tahun_terbit" class="form-control" placeholder="Contoh: 2024" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px;" required>
                </div>

                <div style="margin-top: 16px;">
                    <button type="submit" id="btn-submit" class="btn btn-primary" style="padding: 8px 16px; background-color: #0d6efd; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Simpan Buku</button>
                    <button type="button" id="btn-cancel" class="btn btn-secondary" onclick="resetForm()" style="display: none; margin-left: 6px; padding: 8px 16px; background-color: #6c757d; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Batal Ubah</button>
                </div>
            </form>
        </div>
        @endhasanyrole

        {{-- Tabel Daftar Buku --}}
        <div class="card-box" style="background: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 16px; color: #2c3e50; font-size: 1.25rem; font-weight: 700;">Daftar Data Buku</h3>
            <div style="overflow-x-auto;">
                <table class="api-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f8f9fa; text-align: left; border-bottom: 2px solid #dee2e6;">
                            <th style="padding: 10px;">Judul Buku</th>
                            <th style="padding: 10px;">Penulis</th>
                            <th style="padding: 10px;">Kategori</th>
                            <th style="padding: 10px;">Tahun</th>
                            <th style="padding: 10px;" width="38%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabel-buku-body">
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 16px;">Memuat data buku...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

<script>
    const API_URL = '/api/buku';
    let daftarBuku = [];

    const isStaff = @json(auth()->user()->hasAnyRole(['admin', 'petugas']));

    function tampilkanPesan(pesan, tipe = 'success') {
        const alertBox = document.getElementById('alert-box');
        if (!alertBox) return;

        alertBox.style.display = 'block';
        if (tipe === 'success') {
            alertBox.style.backgroundColor = '#d4edda';
            alertBox.style.color = '#155724';
            alertBox.style.border = '1px solid #c3e6cb';
        } else {
            alertBox.style.backgroundColor = '#f8d7da';
            alertBox.style.color = '#721c24';
            alertBox.style.border = '1px solid #f5c6cb';
        }
        alertBox.innerHTML = pesan;
    }

    async function muatDataBuku() {
        const tbody = document.getElementById('tabel-buku-body');
        try {
            const response = await fetch(API_URL, {
                method: 'GET',
                headers: { 'Accept': 'application/json' }
            });

            if (!response.ok) throw new Error(`Gagal memuat data dari API (HTTP ${response.status})`);

            const result = await response.json();
            daftarBuku = result.data || [];

            document.getElementById('total-buku-count').innerText = daftarBuku.length;

            if (daftarBuku.length === 0) {
                tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; padding: 16px;">Belum ada data buku.</td></tr>`;
                return;
            }

            tbody.innerHTML = '';
            daftarBuku.forEach((item) => {
                const judul = item.judul_buku || item.judul || '-';
                const penulis = item.nama_penulis || item.penulis || '-';
                const tahun = item.tahun_rilis || item.tahun_terbit || item.tahun || '-';
                const status = item.status || 'Tersedia';

                let namaKategori = '-';
                if (typeof item.kategori === 'string') {
                    namaKategori = item.kategori;
                } else if (typeof item.kategori === 'object' && item.kategori !== null) {
                    namaKategori = item.kategori.nama_kategori || item.kategori.nama || '-';
                }

                const sudahDiajukan = status === 'Pengajuan Pinjam' || status === 'Pengajuan' || status === 'Dipinjam';
                const textPinjam = sudahDiajukan ? (status === 'Dipinjam' ? 'Dipinjam' : 'Menunggu') : 'Ajukan Pinjam';
                const disabledAttr = sudahDiajukan 
                    ? 'disabled style="padding: 6px 12px; font-size: 12px; background-color: #6c757d; color: #fff; border: none; border-radius: 4px; opacity: 0.6; cursor: not-allowed; margin-right: 4px;"' 
                    : 'style="padding: 6px 12px; font-size: 12px; background-color: #3b82f6; color: #fff; border: none; border-radius: 4px; cursor: pointer; margin-right: 4px;"';

                let tombolAksi = '';
                if (isStaff) {
                    tombolAksi = `
                        <a href="/buku/${item.id}" class="btn btn-info" style="padding: 6px 12px; font-size: 12px; margin-right: 4px; background-color: #6366f1; color: #fff; border: none; border-radius: 4px; text-decoration: none; display: inline-block;">Detail Buku</a>
                        <button onclick="pinjamBuku(${item.id})" ${disabledAttr}>${textPinjam}</button>
                        <button onclick="pilihBuku(${item.id})" class="btn btn-warning" style="padding: 6px 12px; font-size: 12px; margin-right: 4px; background-color: #eab308; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Ubah</button>
                        <button onclick="hapusBuku(${item.id})" class="btn btn-danger" style="padding: 6px 12px; font-size: 12px; background-color: #ef4444; color: #fff; border: none; border-radius: 4px; cursor: pointer;">Hapus</button>
                    `;
                } else {
                    tombolAksi = `
                        <a href="/buku/${item.id}" class="btn btn-info" style="padding: 6px 12px; font-size: 12px; margin-right: 4px; background-color: #6366f1; color: #fff; border: none; border-radius: 4px; text-decoration: none; display: inline-block;">Detail Buku</a>
                        <button onclick="pinjamBuku(${item.id})" ${disabledAttr}>${textPinjam}</button>
                    `;
                }

                tbody.innerHTML += `
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px; font-weight: 500;">${judul}</td>
                        <td style="padding: 10px;">${penulis}</td>
                        <td style="padding: 10px;">
                            <span style="background-color: #f3f4f6; color: #4b5563; font-size: 12px; padding: 3px 8px; border-radius: 4px; border: 1px solid #e5e7eb;">${namaKategori}</span>
                        </td>
                        <td style="padding: 10px;">${tahun}</td>
                        <td style="padding: 10px;">
                            ${tombolAksi}
                        </td>
                    </tr>
                `;
            });

        } catch (error) {
            tbody.innerHTML = `<tr><td colspan="5" style="text-align: center; color: red; padding: 16px;">Gagal mengambil data buku.</td></tr>`;
            tampilkanPesan('Kesalahan: ' + error.message, 'error');
        }
    }

    async function pinjamBuku(id) {
        if (!confirm('Apakah Anda yakin ingin mengajukan peminjaman buku ini?')) return;

        try {
            const response = await fetch(`/buku/${id}/pinjam`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            if (response.ok && result.success) {
                tampilkanPesan(result.message, 'success');
                await muatDataBuku();
            } else {
                tampilkanPesan(result.message || 'Gagal mengajukan peminjaman.', 'error');
            }
        } catch (error) {
            tampilkanPesan('Terjadi kesalahan: ' + error.message, 'error');
        }
    }

    const bukuForm = document.getElementById('buku-form');
    if (bukuForm) {
        bukuForm.addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = document.getElementById('buku_id').value;
            const payload = {
                kategori_id: document.getElementById('kategori_id').value,
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('penulis').value,
                tahun_terbit: document.getElementById('tahun_terbit').value,
            };

            const isEdit = id !== '';
            const url = isEdit ? `${API_URL}/${id}` : API_URL;
            const method = isEdit ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();

                if (!response.ok) {
                    if (response.status === 422 && result.errors) {
                        const daftarError = Object.values(result.errors).flat().join('<br>');
                        throw new Error(daftarError);
                    }
                    throw new Error(result.message || `Permintaan gagal (HTTP ${response.status})`);
                }

                tampilkanPesan(result.message || 'Data berhasil disimpan.', 'success');
                resetForm();
                await muatDataBuku();
            } catch (error) {
                tampilkanPesan('<strong>Gagal menyimpan data:</strong><br>' + error.message, 'error');
            }
        });
    }

    function pilihBuku(id) {
        if (!isStaff) return;
        const buku = daftarBuku.find(item => item.id === id);
        if (!buku) return;

        const katId = buku.kategori_id || (typeof buku.kategori === 'object' ? buku.kategori.id : '');

        document.getElementById('buku_id').value = buku.id;
        document.getElementById('kategori_id').value = katId;
        document.getElementById('judul').value = buku.judul_buku || buku.judul || '';
        document.getElementById('penulis').value = buku.nama_penulis || buku.penulis || '';
        document.getElementById('tahun_terbit').value = buku.tahun_rilis || buku.tahun_terbit || buku.tahun || '';

        document.getElementById('form-title').innerText = 'Ubah Data Buku';
        document.getElementById('btn-submit').innerText = 'Perbarui Buku';
        document.getElementById('btn-cancel').style.display = 'inline-block';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function resetForm() {
        if (!bukuForm) return;
        bukuForm.reset();
        document.getElementById('buku_id').value = '';
        document.getElementById('form-title').innerText = 'Tambah Buku Baru';
        document.getElementById('btn-submit').innerText = 'Simpan Buku';
        document.getElementById('btn-cancel').style.display = 'none';
    }

    async function hapusBuku(id) {
        if (!isStaff) return;
        if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const result = await response.json();
            if (!response.ok) throw new Error(result.message || `Gagal menghapus buku (HTTP ${response.status})`);

            tampilkanPesan(result.message || 'Buku berhasil dihapus.', 'success');
            await muatDataBuku();
        } catch (error) {
            tampilkanPesan('<strong>Gagal menghapus buku:</strong><br>' + error.message, 'error');
        }
    }

    document.addEventListener('DOMContentLoaded', async () => {
        await muatDataBuku();
    });
</script>
@endsection