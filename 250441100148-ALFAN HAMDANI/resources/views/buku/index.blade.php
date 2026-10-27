@extends('layouts.app')

@section('title', 'Daftar Buku - Perpustakaan Cendekia')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <h2>Data Buku Perpustakaan</h2>

    <!-- Wadah Pesan Error/Sukses -->
    <div id="alert-container"></div>

    <!-- Form Tambah / Ubah Buku -->
    <div class="card mb-4 p-3 shadow-sm">
        <h4 id="form-title">Tambah Buku Baru</h4>
        <form id="buku-form">
            <input type="hidden" id="buku-id">
            <div class="mb-3">
                <label class="form-label">Kategori ID</label>
                <input type="text" id="kategori" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Judul Buku</label>
                <input type="text" id="judul" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Penulis</label>
                <input type="text" id="penulis" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Tahun Terbit</label>
                <input type="number" id="tahun_terbit" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-submit">Simpan</button>
            <button type="button" class="btn btn-secondary d-none" id="btn-cancel" onclick="resetForm()">Batal</button>
        </form>
    </div>

    <!-- Tabel Data Buku -->
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Penulis</th>
                <th>Tahun Terbit</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody id="buku-table-body">
            <!-- Data dimuat secara dinamis -->
        </tbody>
    </table>

    <script>
        const API_URL = '/api/buku';

        function showAlert(message, type = 'danger') {
            document.getElementById('alert-container').innerHTML = `
                                                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                                                    ${message}
                                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                </div>
                                            `;
        }

        // 1. GET: Mengambil data menggunakan Fetch API & async/await
        async function fetchBuku() {
            try {
                const response = await fetch(API_URL);
                if (!response.ok) throw new Error('Gagal mengambil data dari API.');

                const result = await response.json();
                // Karena Controller Anda mengembalikan struktur: { success: true, data: [...] }
                const bukuList = result.data.data || result.data;

                let tbody = document.getElementById('buku-table-body');
                tbody.innerHTML = '';

                bukuList.forEach(buku => {
                    tbody.innerHTML += `
                                                        <tr>
                                                            <td>${buku.kategori_id ?? buku.kategori}</td>
                                                            <td>${buku.judul}</td>
                                                            <td>${buku.penulis}</td>
                                                            <td>${buku.tahun_terbit}</td>
                                                            <td>
                                                                <button class="btn btn-warning btn-sm" onclick="editBuku(${buku.id}, '${buku.kategori_id ?? buku.kategori}', '${buku.judul}', '${buku.penulis}', ${buku.tahun_terbit})">Ubah</button>
                                                                <button class="btn btn-danger btn-sm" onclick="deleteBuku(${buku.id})">Hapus</button>
                                                            </td>
                                                        </tr>
                                                    `;
                });
            } catch (error) {
                showAlert(error.message);
            }
        }

        // 2 & 3. POST & PUT: Simpan atau Perbarui Data Buku
        document.getElementById('buku-form').addEventListener('submit', async function (e) {
            e.preventDefault();

            const id = document.getElementById('buku-id').value;
            const payload = {
                kategori_id: document.getElementById('kategori').value,
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('penulis').value,
                tahun_terbit: document.getElementById('tahun_terbit').value
            };

            let url = API_URL;
            let method = 'POST';

            if (id) {
                url = `${API_URL}/${id}`;
                method = 'PUT'; // Endpoint PUT
            }

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                if (!response.ok) {
                    const errData = await response.json();
                    let errMsg = errData.message || 'Terjadi kesalahan validasi.';
                    if (errData.errors) {
                        errMsg = Object.values(errData.errors).flat().join('<br>');
                    }
                    throw new Error(errMsg);
                }

                resetForm();
                fetchBuku(); // Memperbarui tabel otomatis tanpa reload halaman
                showAlert('Data buku berhasil disimpan!', 'success');
            } catch (error) {
                showAlert(error.message);
            }
        });

        function editBuku(id, kategori, judul, penulis, tahun_terbit) {
            document.getElementById('buku-id').value = id;
            document.getElementById('kategori').value = kategori;
            document.getElementById('judul').value = judul;
            document.getElementById('penulis').value = penulis;
            document.getElementById('tahun_terbit').value = tahun_terbit;

            document.getElementById('form-title').innerText = 'Ubah Data Buku';
            document.getElementById('btn-submit').innerText = 'Perbarui';
            document.getElementById('btn-cancel').classList.remove('d-none');
        }

        // 4. DELETE: Hapus Data dengan Konfirmasi
        async function deleteBuku(id) {
            if (!confirm('Apakah Anda yakin ingin menghapus buku ini?')) return;

            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    method: 'DELETE',
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal menghapus data buku.');

                fetchBuku();
                showAlert('Buku berhasil dihapus.', 'success');
            } catch (error) {
                showAlert(error.message);
            }
        }

        function resetForm() {
            document.getElementById('buku-form').reset();
            document.getElementById('buku-id').value = '';
            document.getElementById('form-title').innerText = 'Tambah Buku Baru';
            document.getElementById('btn-submit').innerText = 'Simpan';
            document.getElementById('btn-cancel').classList.add('d-none');
        }

        fetchBuku();
    </script>
@endsection