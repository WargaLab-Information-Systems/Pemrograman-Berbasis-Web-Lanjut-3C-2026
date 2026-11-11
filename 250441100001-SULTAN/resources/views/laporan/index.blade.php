<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daftar Koleksi Buku') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <style>
                    .form-container { margin-bottom: 25px; padding: 15px; border: 1px solid #ccc; max-width: 450px; border-radius: 5px; }
                    .form-group { margin-bottom: 12px; }
                    .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
                    .form-group input { width: 100%; padding: 8px; box-sizing: border-box; }
                    .tabel-buku { width: 100%; border-collapse: collapse; margin-top: 15px; }
                    .tabel-buku th, .tabel-buku td { border: 1px solid #ddd; padding: 10px; text-align: left; }
                    .tabel-buku th { background-color: #f4f4f4; }
                    .btn { padding: 6px 12px; cursor: pointer; margin-right: 5px; border: none; border-radius: 3px; }
                    .btn-primary { background-color: #0d6efd; color: white; }
                    .btn-secondary { background-color: #6c757d; color: white; }
                    .btn-warning { background-color: #ffc107; color: black; }
                    .btn-danger { background-color: #dc3545; color: white; }
                    .btn-success { background-color: #198754; color: white; }
                </style>

                {{-- HANYA ADMIN & PETUGAS YANG BISA MELIHAT FORM TAMBAH/EDIT BUKU --}}
                @if(auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas')))
                <h3 class="text-lg font-bold mb-3">Form Data Buku</h3>

                <div class="form-container">
                    <form id="formBuku">
                        <input type="hidden" id="bukuId">
                        
                        <div class="form-group">
                            <label for="kategori_id">ID Kategori:</label>
                            <input type="number" id="kategori_id" required placeholder="Masukkan ID Kategori">
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Buku:</label>
                            <input type="text" id="judul" required placeholder="Masukkan Judul Buku">
                        </div>
                        <div class="form-group">
                            <label for="penulis">Penulis:</label>
                            <input type="text" id="penulis" required placeholder="Masukkan Nama Penulis">
                        </div>
                        <div class="form-group">
                            <label for="tahun_terbit">Tahun Terbit:</label>
                            <input type="number" id="tahun_terbit" required placeholder="Contoh: 2026">
                        </div>
                        
                        <button type="submit" id="btnSubmit" class="btn btn-primary">Simpan Buku</button>
                        <button type="button" id="btnCancel" class="btn btn-secondary" style="display:none;" onclick="resetForm()">Batal</button>
                    </form>
                </div>
                @endif

                <h3 class="text-lg font-bold mt-4 mb-3">Daftar Koleksi Buku</h3>
                <table class="tabel-buku" id="tabelBukuMain" 
                       data-can-manage="{{ (auth()->check() && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('petugas'))) ? 'true' : 'false' }}"
                       data-can-borrow="{{ (auth()->check() && auth()->user()->hasRole('siswa')) ? 'true' : 'true' }}">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kategori</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Tahun Terbit</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tabelBuku">
                        
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        const API_URL = '/api/buku';

        document.addEventListener('DOMContentLoaded', fetchBuku);

        async function fetchBuku() {
            const canManageBuku = document.getElementById('tabelBukuMain')?.dataset.canManage === 'true';
            const canBorrowBuku = document.getElementById('tabelBukuMain')?.dataset.canBorrow === 'true';

            try {
                const response = await fetch(API_URL, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error(`Gagal mengambil data! Status: ${response.status}`);

                const result = await response.json();
                const dataBuku = Array.isArray(result) ? result : (result.data || []);

                const tbody = document.getElementById('tabelBuku');
                tbody.innerHTML = '';

                const hasAction = canManageBuku || canBorrowBuku;
                const colspanCount = hasAction ? 6 : 5;

                if (!dataBuku || dataBuku.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="${colspanCount}" style="text-align:center;">Belum ada data buku.</td></tr>`;
                    return;
                }

                dataBuku.forEach((buku, index) => {
                    let actionTd = '';
                    if (hasAction) {
                        let btnBorrow = canBorrowBuku ? `<button class="btn btn-success" onclick="pinjamBuku(${buku.id}, '${buku.judul}')">Pinjam Buku</button>` : '';
                        let btnEdit = canManageBuku ? `<button class="btn btn-warning" onclick="editBuku(${buku.id})">Ubah</button>` : '';
                        let btnDelete = canManageBuku ? `<a href="/buku/${buku.id}/hapus" class="btn btn-danger">Hapus Unsafe</a>` : '';

                        actionTd = `<td>${btnBorrow} ${btnEdit} ${btnDelete}</td>`;
                    }

                    tbody.innerHTML += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${buku.kategori || '-'}</td>
                            <td>${buku.judul}</td>
                            <td>${buku.penulis}</td>
                            <td>${buku.tahun_terbit}</td>
                            ${actionTd}
                        </tr>
                    `;
                });
            } catch (error) {
                alert(`Error: ${error.message}`);
            }
        }

        async function pinjamBuku(id, judul) {
            if (!confirm(`Apakah Anda ingin mengajukan peminjaman untuk buku: "${judul}"?`)) return;

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}';

            try {
                const response = await fetch('/peminjaman/store', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({ buku_id: id, judul: judul })
                });

                const result = await response.json();

                if (!response.ok) throw new Error(result.message || 'Gagal mengajukan peminjaman.');

                alert(`Pengajuan peminjaman untuk "${judul}" berhasil dikirim! Silakan cek menu Riwayat Saya.`);
            } catch (error) {
                alert(`Error: ${error.message}`);
            }
        }

        const formBuku = document.getElementById('formBuku');
        if (formBuku) {
            formBuku.addEventListener('submit', async function (e) {
                e.preventDefault();

                const id = document.getElementById('bukuId').value;
                const data = {
                    kategori_id: document.getElementById('kategori_id').value,
                    judul: document.getElementById('judul').value,
                    penulis: document.getElementById('penulis').value,
                    tahun_terbit: document.getElementById('tahun_terbit').value
                };

                const isEdit = id !== '';
                const url = isEdit ? `${API_URL}/${id}` : API_URL;
                const method = isEdit ? 'PUT' : 'POST';

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(data)
                    });

                    const result = await response.json();

                    if (!response.ok) {
                        if (response.status === 422 && result.errors) {
                            const errors = Object.values(result.errors).flat().join('\n');
                            throw new Error(`Validasi Gagal:\n${errors}`);
                        }
                        throw new Error(result.message || 'Gagal menyimpan data.');
                    }

                    alert(isEdit ? 'Buku berhasil diperbarui!' : 'Buku berhasil ditambahkan!');
                    resetForm();
                    fetchBuku();
                } catch (error) {
                    alert(error.message);
                }
            });
        }

        async function editBuku(id) {
            try {
                const response = await fetch(`${API_URL}/${id}`, {
                    headers: { 'Accept': 'application/json' }
                });

                if (!response.ok) throw new Error('Gagal mengambil detail buku.');

                const result = await response.json();
                const buku = result.data;

                document.getElementById('bukuId').value = buku.id;
                document.getElementById('kategori_id').value = buku.kategori_id || '';
                document.getElementById('judul').value = buku.judul;
                document.getElementById('penulis').value = buku.penulis;
                document.getElementById('tahun_terbit').value = buku.tahun_terbit;

                document.getElementById('btnSubmit').textContent = 'Perbarui Buku';
                document.getElementById('btnCancel').style.display = 'inline-block';
            } catch (error) {
                alert(error.message);
            }
        }

        function resetForm() {
            if (document.getElementById('formBuku')) {
                document.getElementById('bukuId').value = '';
                document.getElementById('formBuku').reset();
                document.getElementById('btnSubmit').textContent = 'Simpan Buku';
                document.getElementById('btnCancel').style.display = 'none';
            }
        }
    </script>
</x-app-layout>