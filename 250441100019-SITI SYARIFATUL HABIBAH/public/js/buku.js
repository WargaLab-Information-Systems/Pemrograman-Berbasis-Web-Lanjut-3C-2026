/**
 * Halaman /buku (Modul 4, disesuaikan untuk Modul 5)
 * Mengambil dan mengubah data buku lewat API menggunakan Fetch API + async/await.
 *
 *   GET    /api/buku        -> menampilkan tabel   (Soal 1)
 *   POST   /api/buku        -> tambah buku         (Soal 2)
 *   PUT    /api/buku/{id}   -> ubah buku           (Soal 3)
 *   DELETE /api/buku/{id}   -> hapus buku          (Soal 4)
 *
 * Penanganan kesalahan (Soal 5): setiap request memeriksa response.ok
 * dan dibungkus try/catch.
 *
 * Modul 5: API kini dilindungi login + permission.
 *   - Request membawa token CSRF (X-CSRF-TOKEN) dan cookie session.
 *   - Formulir dan tombol Ubah/Hapus hanya ada untuk yang punya permission
 *     'kelola data buku' (admin dan petugas). Siswa hanya melihat daftar.
 */
(function () {
    'use strict';

    // ---------------------------------------------------------------
    // Elemen halaman dan konfigurasi
    // ---------------------------------------------------------------
    const root = document.getElementById('app-buku');
    const API_URL = root.dataset.apiUrl;        // dari Blade: url('/api/buku')
    const DETAIL_URL = root.dataset.detailUrl;  // dari Blade: url('/buku')

    // Modul 5: apakah pengguna ini boleh mengelola buku? (ditentukan Blade lewat @can)
    const BISA_KELOLA = root.dataset.kelola === 'true';

    // Token CSRF dari <meta name="csrf-token">.
    // Wajib untuk POST/PUT/DELETE karena API memakai session login.
    const tokenCsrf = document.querySelector('meta[name="csrf-token"]');

    // Header wajib untuk API Laravel: minta balasan JSON.
    const HEADERS = {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': tokenCsrf ? tokenCsrf.content : '',
    };

    const tbody = document.getElementById('tbody-buku');
    const form = document.getElementById('form-buku');            // null jika bukan admin/petugas
    const judulForm = document.getElementById('judul-form');
    const tombolSimpan = document.getElementById('tombol-simpan');
    const tombolBatal = document.getElementById('tombol-batal');
    const kotakPesan = document.getElementById('pesan');
    const tombolSebelumnya = document.getElementById('tombol-sebelumnya');
    const tombolBerikutnya = document.getElementById('tombol-berikutnya');
    const infoHalaman = document.getElementById('info-halaman');

    const FIELD = ['kategori_id', 'judul', 'penulis', 'tahun_terbit'];
    const input = {};
    FIELD.forEach(function (nama) {
        input[nama] = document.getElementById(nama);
    });

    // ---------------------------------------------------------------
    // State
    // ---------------------------------------------------------------
    let daftarBuku = [];       // data buku yang sedang tampil di tabel
    let halamanSekarang = 1;
    let halamanTerakhir = 1;
    let idDiedit = null;       // null = mode tambah, angka = mode ubah
    let sedangMengirim = false;

    // ---------------------------------------------------------------
    // Fungsi bantu
    // ---------------------------------------------------------------

    // Membaca isi JSON dengan aman. Jika bukan JSON, kembalikan objek kosong.
    async function bacaJson(response) {
        try {
            return await response.json();
        } catch (error) {
            return {};
        }
    }

    function tampilkanPesan(jenis, teks) {
        kotakPesan.className = 'alert ' + (jenis === 'sukses' ? 'alert-sukses' : 'alert-error');
        kotakPesan.textContent = teks;
        kotakPesan.hidden = false;
    }

    function bersihkanPesan() {
        kotakPesan.hidden = true;
        kotakPesan.textContent = '';
    }

    function pesanGagal(response, hasil, aksi) {
        // Modul 5: kegagalan akibat login atau hak akses
        if (response.status === 401) {
            return 'Sesi Anda telah berakhir. Silakan login kembali.';
        }
        if (response.status === 403) {
            return 'Anda tidak memiliki hak akses untuk operasi ini.';
        }
        if (response.status === 419) {
            return 'Sesi kedaluwarsa. Muat ulang halaman, lalu coba lagi.';
        }
        if (response.status === 404) {
            return 'Buku tidak ditemukan. Kemungkinan sudah dihapus.';
        }
        if (hasil.message) {
            return 'Gagal ' + aksi + ': ' + hasil.message;
        }
        return 'Gagal ' + aksi + ' (status ' + response.status + ').';
    }

    const PESAN_TIDAK_TERHUBUNG =
        'Tidak dapat terhubung ke API. Pastikan server berjalan, lalu coba lagi.';

    // Menampilkan error validasi (422) di bawah masing-masing kolom.
    function tampilkanErrorValidasi(errors) {
        FIELD.forEach(function (nama) {
            const pesanField = errors[nama];
            if (pesanField && pesanField.length > 0) {
                document.getElementById('error-' + nama).textContent = pesanField[0];
                input[nama].classList.add('input-error');
            }
        });
    }

    function bersihkanErrorValidasi() {
        FIELD.forEach(function (nama) {
            document.getElementById('error-' + nama).textContent = '';
            input[nama].classList.remove('input-error');
        });
    }

    function aturSedangMengirim(status) {
        sedangMengirim = status;
        tombolSimpan.disabled = status;
        tombolBatal.disabled = status;
        tombolSimpan.textContent = status
            ? 'Menyimpan...'
            : (idDiedit === null ? 'Tambah Buku' : 'Simpan Perubahan');
    }

    // ---------------------------------------------------------------
    // Menggambar tabel (memakai textContent, jadi aman dari XSS)
    // ---------------------------------------------------------------
    function buatSel(teks) {
        const td = document.createElement('td');
        td.textContent = teks;
        return td;
    }

    function buatTombol(teks, aksi, id, kelas) {
        const tombol = document.createElement('button');
        tombol.type = 'button';
        tombol.textContent = teks;
        tombol.className = 'button button-kecil ' + kelas;
        tombol.dataset.aksi = aksi;
        tombol.dataset.id = id;
        return tombol;
    }

    function tampilkanBarisInfo(teks) {
        const tr = document.createElement('tr');
        const td = document.createElement('td');
        td.colSpan = 6;
        td.className = 'info-row';
        td.textContent = teks;
        tr.append(td);
        tbody.replaceChildren(tr);
    }

    function gambarTabel(nomorAwal) {
        if (daftarBuku.length === 0) {
            tampilkanBarisInfo('Belum ada data buku.');
            return;
        }

        const baris = daftarBuku.map(function (buku, indeks) {
            const tr = document.createElement('tr');

            const sel = document.createElement('td');
            sel.className = 'sel-aksi';

            const detail = document.createElement('a');
            detail.href = DETAIL_URL + '/' + buku.id;
            detail.textContent = 'Detail';
            detail.className = 'button button-kecil';

            sel.append(detail);

            // Modul 5: tombol Ubah dan Hapus hanya untuk yang berhak mengelola buku
            if (BISA_KELOLA) {
                sel.append(
                    buatTombol('Ubah', 'ubah', buku.id, 'button-ubah'),
                    buatTombol('Hapus', 'hapus', buku.id, 'button-hapus')
                );
            }

            // Nama atribut mengikuti BukuResource:
            //   buku.kategori (teks), buku.judul, buku.penulis, buku.tahun
            tr.append(
                buatSel(String(nomorAwal + indeks)),
                buatSel(buku.kategori ?? '-'),
                buatSel(buku.judul),
                buatSel(buku.penulis),
                buatSel(String(buku.tahun)),
                sel
            );
            return tr;
        });

        tbody.replaceChildren(...baris);
    }

    function gambarPaginasi(meta) {
        infoHalaman.textContent =
            'Halaman ' + meta.current_page + ' dari ' + meta.last_page +
            ' (total ' + meta.total + ' buku)';
        tombolSebelumnya.disabled = meta.current_page <= 1;
        tombolBerikutnya.disabled = meta.current_page >= meta.last_page;
    }

    // ---------------------------------------------------------------
    // SOAL 1 - Mengambil data (GET /api/buku)
    // Mengembalikan true jika berhasil, false jika gagal.
    // ---------------------------------------------------------------
    async function muatBuku(halaman) {
        halaman = halaman || halamanSekarang;
        tampilkanBarisInfo('Memuat data...');

        try {
            const response = await fetch(API_URL + '?page=' + halaman, { headers: HEADERS });
            const hasil = await bacaJson(response);

            if (!response.ok) {
                tampilkanBarisInfo('Data gagal dimuat.');
                tampilkanPesan('error', pesanGagal(response, hasil, 'mengambil data buku'));
                return false;
            }

            // Halaman yang diminta sudah kosong (misalnya data terakhir di halaman itu
            // baru dihapus): mundur satu halaman.
            if (hasil.data.length === 0 && halaman > 1) {
                return await muatBuku(halaman - 1);
            }

            daftarBuku = hasil.data;
            halamanSekarang = hasil.meta.current_page;
            halamanTerakhir = hasil.meta.last_page;

            gambarTabel(hasil.meta.from ?? 1);
            gambarPaginasi(hasil.meta);
            return true;
        } catch (error) {
            tampilkanBarisInfo('Data tidak dapat ditampilkan.');
            tampilkanPesan('error', PESAN_TIDAK_TERHUBUNG);
            return false;
        }
    }

    // ---------------------------------------------------------------
    // Mode formulir: tambah atau ubah (hanya dipakai admin/petugas)
    // ---------------------------------------------------------------
    function kosongkanForm() {
        form.reset();
        idDiedit = null;
        judulForm.textContent = 'Tambah Buku';
        tombolSimpan.textContent = 'Tambah Buku';
        tombolBatal.hidden = true;
        bersihkanErrorValidasi();
    }

    // SOAL 3 - tampilkan data buku yang dipilih pada formulir
    function mulaiUbah(id) {
        const buku = daftarBuku.find(function (item) {
            return item.id === id;
        });
        if (!buku) {
            return;
        }

        bersihkanPesan();
        bersihkanErrorValidasi();

        idDiedit = buku.id;
        input.kategori_id.value = buku.kategori_id;
        input.judul.value = buku.judul;
        input.penulis.value = buku.penulis;
        input.tahun_terbit.value = buku.tahun;

        judulForm.textContent = 'Ubah Buku (ID ' + buku.id + ')';
        tombolSimpan.textContent = 'Simpan Perubahan';
        tombolBatal.hidden = false;

        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        input.judul.focus();
    }

    // ---------------------------------------------------------------
    // SOAL 2 dan 3 - Simpan: POST (tambah) atau PUT (ubah)
    // ---------------------------------------------------------------
    async function simpanBuku(event) {
        event.preventDefault();
        if (sedangMengirim) {
            return;
        }

        bersihkanPesan();
        bersihkanErrorValidasi();

        const payload = {
            kategori_id: input.kategori_id.value,
            judul: input.judul.value.trim(),
            penulis: input.penulis.value.trim(),
            tahun_terbit: input.tahun_terbit.value.trim(),
        };

        const modeUbah = idDiedit !== null;
        const url = modeUbah ? API_URL + '/' + idDiedit : API_URL;
        const method = modeUbah ? 'PUT' : 'POST';
        const aksi = modeUbah ? 'mengubah buku' : 'menambah buku';

        aturSedangMengirim(true);

        try {
            const response = await fetch(url, {
                method: method,
                headers: HEADERS,
                body: JSON.stringify(payload),
            });
            const hasil = await bacaJson(response);

            // Validasi gagal (422): tampilkan pesan per kolom, form TIDAK dikosongkan.
            if (response.status === 422) {
                tampilkanErrorValidasi(hasil.errors || {});
                tampilkanPesan('error', 'Data belum valid. Periksa isian yang diberi tanda merah.');
                return;
            }

            // Kegagalan lain (401, 403, 404, 500, dst.): jangan tampilkan pesan sukses.
            if (!response.ok) {
                tampilkanPesan('error', pesanGagal(response, hasil, aksi));
                if (response.status === 404) {
                    kosongkanForm();
                    await muatBuku();
                }
                return;
            }

            // Berhasil: kosongkan formulir, lalu muat ulang tabel tanpa reload halaman.
            // Buku baru tampil paling atas di halaman 1 (API mengurutkan terbaru dulu).
            const pesanSukses = hasil.message || (modeUbah ? 'Buku berhasil diperbarui.' : 'Buku berhasil ditambahkan.');
            kosongkanForm();
            const berhasilMuat = await muatBuku(modeUbah ? halamanSekarang : 1);

            if (berhasilMuat) {
                tampilkanPesan('sukses', pesanSukses);
            }
        } catch (error) {
            tampilkanPesan('error', 'Data belum tersimpan. ' + PESAN_TIDAK_TERHUBUNG);
        } finally {
            aturSedangMengirim(false);
        }
    }

    // ---------------------------------------------------------------
    // SOAL 4 - Hapus (DELETE /api/buku/{id}) dengan konfirmasi
    // ---------------------------------------------------------------
    async function hapusBuku(id) {
        const buku = daftarBuku.find(function (item) {
            return item.id === id;
        });
        if (!buku) {
            return;
        }

        // Jika pengguna membatalkan, tidak ada request yang dikirim.
        if (!window.confirm('Hapus buku "' + buku.judul + '"?')) {
            return;
        }

        bersihkanPesan();

        try {
            const response = await fetch(API_URL + '/' + id, {
                method: 'DELETE',
                headers: HEADERS,
            });
            const hasil = await bacaJson(response);

            if (!response.ok) {
                // Contoh: 403 (tidak berhak), 409 (buku masih punya riwayat peminjaman),
                // atau 404 (sudah terhapus).
                tampilkanPesan('error', pesanGagal(response, hasil, 'menghapus buku'));
                if (response.status === 404) {
                    await muatBuku();
                }
                return;
            }

            if (idDiedit === id) {
                kosongkanForm();
            }

            const berhasilMuat = await muatBuku();
            if (berhasilMuat) {
                tampilkanPesan('sukses', hasil.message || 'Buku berhasil dihapus.');
            }
        } catch (error) {
            tampilkanPesan('error', 'Buku belum terhapus. ' + PESAN_TIDAK_TERHUBUNG);
        }
    }

    // ---------------------------------------------------------------
    // Pasang event
    // ---------------------------------------------------------------

    // Formulir hanya ada untuk admin/petugas. Untuk siswa, bagian ini dilewati.
    if (BISA_KELOLA && form) {
        form.addEventListener('submit', simpanBuku);

        tombolBatal.addEventListener('click', function () {
            kosongkanForm();
            bersihkanPesan();
        });
    }

    // Satu listener untuk semua tombol Ubah/Hapus di tabel (event delegation).
    tbody.addEventListener('click', function (event) {
        const tombol = event.target.closest('button[data-aksi]');
        if (!tombol) {
            return;
        }
        const id = Number(tombol.dataset.id);

        if (tombol.dataset.aksi === 'ubah') {
            mulaiUbah(id);
        } else if (tombol.dataset.aksi === 'hapus') {
            hapusBuku(id);
        }
    });

    tombolSebelumnya.addEventListener('click', function () {
        if (halamanSekarang > 1) {
            muatBuku(halamanSekarang - 1);
        }
    });

    tombolBerikutnya.addEventListener('click', function () {
        if (halamanSekarang < halamanTerakhir) {
            muatBuku(halamanSekarang + 1);
        }
    });

    // Muat data saat halaman pertama kali dibuka.
    muatBuku(1);
})();
