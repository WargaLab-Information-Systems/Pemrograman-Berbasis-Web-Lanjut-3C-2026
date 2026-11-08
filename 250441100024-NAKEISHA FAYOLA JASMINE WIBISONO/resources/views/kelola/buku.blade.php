<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Kelola Buku</h2>
    </x-slot>

    <style>
        .crud-buku .card {
            background: #fff;
            border-radius: 8px;
            padding: 16px 20px;
            margin-bottom: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
        }

        .crud-buku .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .crud-buku .field label {
            display: block;
            font-size: 14px;
            margin-bottom: 4px;
        }

        .crud-buku .field input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .crud-buku .field .error {
            color: #c0392b;
            font-size: 12px;
            min-height: 16px;
            margin-top: 2px;
        }

        .crud-buku button {
            padding: 8px 14px;
            border: 0;
            border-radius: 4px;
            cursor: pointer;
            color: #fff;
            background: #2d6cdf;
        }

        .crud-buku button.secondary {
            background: #7f8c8d;
        }

        .crud-buku button.warning {
            background: #e67e22;
        }

        .crud-buku button.danger {
            background: #c0392b;
        }

        .crud-buku button:disabled {
            opacity: .6;
            cursor: not-allowed;
        }

        .crud-buku table {
            width: 100%;
            border-collapse: collapse;
        }

        .crud-buku th,
        .crud-buku td {
            padding: 8px 10px;
            border-bottom: 1px solid #e3e6ea;
            text-align: left;
            font-size: 14px;
        }

        .crud-buku th {
            background: #eef1f5;
        }

        .crud-buku .alert {
            padding: 10px 14px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        .crud-buku .alert.sukses {
            background: #e6f6ea;
            color: #1e7b34;
        }

        .crud-buku .alert.error {
            background: #fdecea;
            color: #b3261e;
        }

        .crud-buku .pagination {
            margin-top: 12px;
            display: flex;
            gap: 10px;
            align-items: center;
        }
    </style>

    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 crud-buku">
            <div id="alert" class="alert" hidden></div>

            <div class="card">
                <h3 id="form-title" class="font-semibold mb-3">Tambah Buku</h3>
                <form id="form-buku" novalidate>
                    <input type="hidden" id="buku-id">
                    <div class="grid">
                        <div class="field">
                            <label for="kategori_id">ID Kategori</label>
                            <input type="number" id="kategori_id" min="1" placeholder="Masukkan ID kategori">
                            <div class="error" id="err-kategori_id"></div>
                        </div>
                        <div class="field">
                            <label for="judul">Judul</label>
                            <input type="text" id="judul">
                            <div class="error" id="err-judul"></div>
                        </div>
                        <div class="field">
                            <label for="penulis">Penulis</label>
                            <input type="text" id="penulis">
                            <div class="error" id="err-penulis"></div>
                        </div>
                        <div class="field">
                            <label for="tahun_terbit">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit">
                            <div class="error" id="err-tahun_terbit"></div>
                        </div>
                    </div>
                    <div style="margin-top:12px; display:flex; gap:8px;">
                        <button type="submit" id="btn-simpan">Simpan</button>
                        <button type="button" id="btn-batal" class="secondary" hidden>Batal</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <table>
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
                    <tbody id="tabel-buku"></tbody>
                </table>
                <div class="pagination">
                    <button type="button" id="btn-prev" class="secondary">&laquo; Sebelumnya</button>
                    <span id="info-halaman"></span>
                    <button type="button" id="btn-next" class="secondary">Berikutnya &raquo;</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Endpoint dilindungi session login (middleware auth) dan permission "kelola-data-buku".
        const API_URL = "{{ route('data.buku.index', [], false) }}";
        const CSRF = document.querySelector('meta[name="csrf-token"]')?.content ?? '';
        const HEADERS = {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF,
        };
        const FIELDS = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];

        let daftarBuku = [];
        let halaman = 1;
        let halamanTerakhir = 1;

        const tabel = document.getElementById('tabel-buku');
        const form = document.getElementById('form-buku');
        const alertBox = document.getElementById('alert');

        // ---------- Util ----------
        function esc(teks) {
            const div = document.createElement('div');
            div.textContent = teks ?? '';
            return div.innerHTML;
        }

        function tampilPesan(teks, tipe) {
            alertBox.textContent = teks;
            alertBox.className = 'alert ' + tipe;
            alertBox.hidden = false;
        }

        function pesanGagal(status, hasil, bawaan) {
            if (status === 401 || status === 419) return 'Sesi Anda berakhir. Silakan login kembali.';
            if (status === 403) return 'Anda tidak memiliki izin untuk melakukan aksi ini.';
            return (hasil && hasil.message) || bawaan;
        }

        function bersihkanError() {
            FIELDS.forEach(f => document.getElementById('err-' + f).textContent = '');
            alertBox.hidden = true;
        }

        function tampilErrorField(errors) {
            Object.keys(errors).forEach(field => {
                const el = document.getElementById('err-' + field);
                if (el) el.textContent = errors[field][0];
            });
        }

        function resetForm() {
            form.reset();
            document.getElementById('buku-id').value = '';
            document.getElementById('form-title').textContent = 'Tambah Buku';
            document.getElementById('btn-simpan').textContent = 'Simpan';
            document.getElementById('btn-batal').hidden = true;
        }

        // ---------- Ambil data (GET) ----------
        async function muatBuku() {
            tabel.innerHTML = '<tr><td colspan="6">Memuat data...</td></tr>';
            try {
                const response = await fetch(`${API_URL}?page=${halaman}`, {
                    headers: HEADERS,
                    credentials: 'same-origin',
                });

                if (!response.ok) {
                    throw new Error(pesanGagal(response.status, null, `Gagal mengambil data (status ${response.status}).`));
                }

                const hasil = await response.json();

                // Kalau halaman terakhir jadi kosong (misal setelah hapus), mundur satu halaman
                if (hasil.data.length === 0 && halaman > 1) {
                    halaman--;
                    return muatBuku();
                }

                daftarBuku = hasil.data;
                halamanTerakhir = hasil.meta.last_page;
                renderTabel(hasil.meta);
            } catch (error) {
                tabel.innerHTML = '<tr><td colspan="6">Data tidak dapat ditampilkan.</td></tr>';
                const apiMati = error instanceof TypeError;
                tampilPesan(apiMati ? 'Server tidak dapat diakses. Pastikan server berjalan.' : error.message, 'error');
            }
        }

        function renderTabel(meta) {
            if (daftarBuku.length === 0) {
                tabel.innerHTML = '<tr><td colspan="6">Belum ada data buku.</td></tr>';
            } else {
                tabel.innerHTML = daftarBuku.map((b, i) => `
                    <tr>
                        <td>${(meta.from ?? 1) + i}</td>
                        <td>${esc(b.kategori)}</td>
                        <td>${esc(b.judul)}</td>
                        <td>${esc(b.penulis)}</td>
                        <td>${esc(String(b.tahun))}</td>
                        <td>
                            <button type="button" class="warning" data-aksi="ubah" data-id="${b.id}">Ubah</button>
                            <button type="button" class="danger" data-aksi="hapus" data-id="${b.id}">Hapus</button>
                        </td>
                    </tr>
                `).join('');
            }

            document.getElementById('info-halaman').textContent = `Halaman ${meta.current_page} dari ${meta.last_page}`;
            document.getElementById('btn-prev').disabled = meta.current_page <= 1;
            document.getElementById('btn-next').disabled = meta.current_page >= meta.last_page;
        }

        // ---------- Tambah (POST) dan ubah (PUT) ----------
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            bersihkanError();

            const id = document.getElementById('buku-id').value;
            const isEdit = id !== '';
            const tahun = document.getElementById('tahun_terbit').value;
            const kategori = document.getElementById('kategori_id').value;

            const payload = {
                kategori_id: kategori === '' ? '' : Number(kategori),
                judul: document.getElementById('judul').value,
                penulis: document.getElementById('penulis').value,
                tahun_terbit: tahun === '' ? '' : Number(tahun),
            };

            const btn = document.getElementById('btn-simpan');
            btn.disabled = true;

            try {
                const response = await fetch(isEdit ? `${API_URL}/${id}` : API_URL, {
                    method: isEdit ? 'PUT' : 'POST',
                    headers: HEADERS,
                    credentials: 'same-origin',
                    body: JSON.stringify(payload),
                });

                const hasil = await response.json().catch(() => ({}));

                if (!response.ok) {
                    if (response.status === 422) {
                        tampilErrorField(hasil.errors || {});
                        tampilPesan('Data belum valid. Periksa kembali isian formulir.', 'error');
                    } else {
                        tampilPesan(pesanGagal(response.status, hasil, `Gagal menyimpan data (status ${response.status}).`), 'error');
                    }
                    return; // jangan tampilkan pesan sukses
                }

                tampilPesan(hasil.message || 'Data berhasil disimpan.', 'sukses');
                resetForm();
                if (!isEdit) halaman = 1; // buku baru ada di halaman pertama (urut terbaru)
                await muatBuku();
            } catch (error) {
                tampilPesan('Server tidak dapat diakses. Pastikan server berjalan.', 'error');
            } finally {
                btn.disabled = false;
            }
        });

        // ---------- Tombol Ubah dan Hapus ----------
        tabel.addEventListener('click', async (e) => {
            const tombol = e.target.closest('button[data-aksi]');
            if (!tombol) return;

            const id = tombol.dataset.id;

            if (tombol.dataset.aksi === 'ubah') {
                const buku = daftarBuku.find(b => String(b.id) === id);
                if (!buku) return;

                bersihkanError();
                document.getElementById('buku-id').value = buku.id;
                document.getElementById('kategori_id').value = buku.kategori_id;
                document.getElementById('judul').value = buku.judul;
                document.getElementById('penulis').value = buku.penulis;
                document.getElementById('tahun_terbit').value = buku.tahun;
                document.getElementById('form-title').textContent = 'Ubah Buku';
                document.getElementById('btn-simpan').textContent = 'Simpan Perubahan';
                document.getElementById('btn-batal').hidden = false;
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
                return;
            }

            if (tombol.dataset.aksi === 'hapus') {
                if (!confirm('Yakin ingin menghapus buku ini?')) return;

                bersihkanError();
                try {
                    const response = await fetch(`${API_URL}/${id}`, {
                        method: 'DELETE',
                        headers: HEADERS,
                        credentials: 'same-origin',
                    });
                    const hasil = await response.json().catch(() => ({}));

                    if (!response.ok) {
                        tampilPesan(pesanGagal(response.status, hasil, `Gagal menghapus data (status ${response.status}).`), 'error');
                        return;
                    }

                    tampilPesan(hasil.message || 'Buku berhasil dihapus.', 'sukses');
                    await muatBuku();
                } catch (error) {
                    tampilPesan('Server tidak dapat diakses. Pastikan server berjalan.', 'error');
                }
            }
        });

        document.getElementById('btn-batal').addEventListener('click', () => {
            bersihkanError();
            resetForm();
        });

        // ---------- Pagination ----------
        document.getElementById('btn-prev').addEventListener('click', () => {
            if (halaman > 1) {
                halaman--;
                muatBuku();
            }
        });
        document.getElementById('btn-next').addEventListener('click', () => {
            if (halaman < halamanTerakhir) {
                halaman++;
                muatBuku();
            }
        });

        muatBuku();
    </script>
</x-app-layout>