@extends('layouts.perpus')

@section('title', 'Daftar Buku')

@section('content')
<style>
    .daftar-buku .card {
        background: #fff;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, .1);
    }

    .daftar-buku table {
        width: 100%;
        border-collapse: collapse;
    }

    .daftar-buku th,
    .daftar-buku td {
        padding: 8px 10px;
        border-bottom: 1px solid #e3e6ea;
        text-align: left;
        font-size: 14px;
    }

    .daftar-buku th {
        background: #eef1f5;
    }

    .daftar-buku .alert {
        padding: 10px 14px;
        border-radius: 4px;
        margin-bottom: 16px;
    }

    .daftar-buku .alert.error {
        background: #fdecea;
        color: #b3261e;
    }

    .daftar-buku .alert.sukses {
        background: #e6f6ea;
        color: #1e7b34;
    }

    .daftar-buku .pagination {
        margin-top: 12px;
        display: flex;
        gap: 10px;
        align-items: center;
    }

    .daftar-buku button,
    .daftar-buku .tombol {
        padding: 6px 12px;
        border: 0;
        border-radius: 4px;
        cursor: pointer;
        color: #fff;
        background: #7f8c8d;
        text-decoration: none;
        font-size: 14px;
        display: inline-block;
    }

    .daftar-buku .tombol.utama {
        background: #2d6cdf;
    }

    .daftar-buku button:disabled {
        opacity: .6;
        cursor: not-allowed;
    }

    .daftar-buku .aksi-atas {
        display: flex;
        gap: 8px;
        margin-bottom: 12px;
    }
</style>

<div class="daftar-buku">
    <h2 class="page-title">Daftar Buku</h2>

    <div class="aksi-atas">
        <a href="{{ route('dashboard') }}" class="tombol">&larr; Dashboard</a>
        @can('kelola-data-buku')
        <a href="{{ route('kelola.buku') }}" class="tombol utama">Kelola Buku (Tambah / Ubah / Hapus)</a>
        @endcan
    </div>

    <div id="alert" class="alert" hidden></div>

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
            <button type="button" id="btn-prev">&laquo; Sebelumnya</button>
            <span id="info-halaman"></span>
            <button type="button" id="btn-next">Berikutnya &raquo;</button>
        </div>
    </div>
</div>

<script>
    // Modul 4: data diambil lewat Fetch API + async/await dari endpoint yang dilindungi session login.
    const API_URL = "{{ route('data.buku.index', [], false) }}";
    const URL_DETAIL = "{{ url('/buku') }}";
    const HEADERS = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest'
    };

    let halaman = 1;
    let halamanTerakhir = 1;

    const tabel = document.getElementById('tabel-buku');
    const alertBox = document.getElementById('alert');

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

    async function muatBuku() {
        tabel.innerHTML = '<tr><td colspan="6">Memuat data...</td></tr>';
        alertBox.hidden = true;

        try {
            const response = await fetch(`${API_URL}?page=${halaman}`, {
                headers: HEADERS,
                credentials: 'same-origin',
            });

            if (!response.ok) {
                if (response.status === 401 || response.status === 419) {
                    throw new Error('Sesi Anda berakhir. Silakan login kembali.');
                }
                if (response.status === 403) {
                    throw new Error('Anda tidak memiliki izin untuk melihat daftar buku.');
                }
                throw new Error(`Gagal mengambil data (status ${response.status}).`);
            }

            const hasil = await response.json();
            halamanTerakhir = hasil.meta.last_page;
            renderTabel(hasil.data, hasil.meta);
        } catch (error) {
            tabel.innerHTML = '<tr><td colspan="6">Data tidak dapat ditampilkan.</td></tr>';
            const apiMati = error instanceof TypeError;
            tampilPesan(apiMati ? 'Server tidak dapat diakses. Pastikan server berjalan.' : error.message, 'error');
        }
    }

    function renderTabel(daftar, meta) {
        if (daftar.length === 0) {
            tabel.innerHTML = '<tr><td colspan="6">Belum ada data buku.</td></tr>';
        } else {
            tabel.innerHTML = daftar.map((b, i) => `
                    <tr>
                        <td>${(meta.from ?? 1) + i}</td>
                        <td>${esc(b.kategori)}</td>
                        <td>${esc(b.judul)}</td>
                        <td>${esc(b.penulis)}</td>
                        <td>${esc(String(b.tahun))}</td>
                        <td><a class="tombol utama" href="${URL_DETAIL}/${b.id}">Detail</a></td>
                    </tr>
                `).join('');
        }

        document.getElementById('info-halaman').textContent = `Halaman ${meta.current_page} dari ${meta.last_page}`;
        document.getElementById('btn-prev').disabled = meta.current_page <= 1;
        document.getElementById('btn-next').disabled = meta.current_page >= meta.last_page;
    }

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
@endsection