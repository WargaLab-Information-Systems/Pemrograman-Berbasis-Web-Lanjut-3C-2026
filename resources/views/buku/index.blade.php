
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

@can('kelola data buku')
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
@endcan

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

        <tbody>
            @forelse ($bukus as $buku)
                <tr>
                    <td>{{ $bukus->firstItem() + $loop->index }}</td>
                    <td>{{ $buku->kategori->nama ?? '-' }}</td>
                    {{-- [A1 - RENTAN] {!! !!} tidak melakukan escaping HTML --}}
                    <td>{!! $buku->judul !!}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ $buku->tahun_terbit }}</td>
                    <td>
                        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-light btn-sm">Detail</a>

                        @guest
                            <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login untuk Meminjam</a>
                        @endguest

                        @can('mengajukan peminjaman buku')
                            <form method="POST" action="{{ route('peminjaman.store') }}" class="inline-form">
                                @csrf
                                <input type="hidden" name="buku_id" value="{{ $buku->id }}">
                                <button type="submit" class="btn btn-sm">Ajukan Pinjam</button>
                            </form>
                        @endcan

                        @can('kelola data buku')
                            <button type="button" class="btn-edit" onclick="isiForm({{ $buku->id }})">Ubah</button>
                            <button type="button" class="btn-delete"
                                    onclick="hapusBuku({{ $buku->id }}, @js(strip_tags($buku->judul)))">Hapus</button>
                        @endcan
                    </td>
                </tr>
            @empty
                <tr><td colspan="6">Belum ada data buku.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="book-pagination">
    {{ $bukus->links() }}
</div>

@if (session('success'))
    <p class="book-message" role="status">{{ session('success') }}</p>
@endif

@can('kelola data buku')
<script>
    const API_URL = '/api/buku';
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    const formBuku = document.getElementById('formBuku');
    const pesanForm = document.getElementById('pesanForm');

    async function bacaRespons(response) {
        let hasil;
        try {
            hasil = await response.json();
        } catch (error) {
            throw new Error('Respons server bukan JSON yang valid.');
        }

        if (!response.ok) {
            if (hasil.errors) {
                throw new Error(Object.values(hasil.errors).flat().join('\n') || hasil.message || 'Permintaan gagal.');
            }
            throw new Error(hasil.message || `Permintaan gagal (${response.status}).`);
        }

        return hasil;
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
            const response = await fetch(`${API_URL}/${id}`, { headers: { 'Accept': 'application/json' } });
            const hasil = await bacaRespons(response);
            const detail = hasil.data?.data ?? hasil.data;

            document.getElementById('buku_id').value = detail.id;
            document.getElementById('judul').value = detail.judul ?? '';
            document.getElementById('penulis').value = detail.penulis ?? '';
            document.getElementById('tahun_terbit').value = detail.tahunTerbit ?? detail.tahun_terbit ?? '';

            const pilihanKategori = document.getElementById('kategori_id');
            if (detail.kategori_id !== undefined) {
                pilihanKategori.value = detail.kategori_id;
            } else {
                const opsi = Array.from(pilihanKategori.options).find(o => o.textContent.trim() === detail.kategori);
                pilihanKategori.value = opsi ? opsi.value : '';
            }

            document.getElementById('judulForm').textContent = 'Ubah Buku';
            document.getElementById('btnSimpan').textContent = 'Simpan Perubahan';
            document.getElementById('btnBatal').hidden = false;
            document.getElementById('judulForm').scrollIntoView({ behavior: 'smooth', block: 'center' });
        } catch (error) {
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
            tahun_terbit: Number(document.getElementById('tahun_terbit').value)
        };

        try {
            const response = await fetch(id ? `${API_URL}/${id}` : API_URL, {
                method: id ? 'PUT' : 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN
                },
                body: JSON.stringify(dataBuku)
            });

            await bacaRespons(response);
            location.href = '/buku';
        } catch (error) {
            pesanForm.textContent = error.message;
        }
    });

    async function hapusBuku(id, judul) {
        if (!confirm(`Apakah kamu yakin ingin menghapus buku "${judul}"?`)) {
            return;
        }

        try {
            const response = await fetch(`${API_URL}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF_TOKEN }
            });

            await bacaRespons(response);
            location.reload();
        } catch (error) {
            pesanForm.textContent = error.message;
        }
    }
</script>
@endcan

@endsection
