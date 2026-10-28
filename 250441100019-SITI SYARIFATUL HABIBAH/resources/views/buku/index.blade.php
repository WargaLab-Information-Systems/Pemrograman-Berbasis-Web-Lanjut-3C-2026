@extends('layouts.app')

@section('title', 'Daftar Buku')

@section('content')

    <div class="page-header">
        <h2>Daftar Buku</h2>
        <p>
            Jelajahi dan akses ribuan koleksi buku digital kapan saja dan di mana saja.
        </p>
    </div>

    <div
        id="app-buku"
        data-api-url="{{ url('/api/buku') }}"
        data-detail-url="{{ url('/buku') }}"
    >

        <div id="pesan" class="alert" role="alert" hidden></div>

        <section class="form-card">

            <h3 id="judul-form">Tambah Buku</h3>

            <form id="form-buku" novalidate>

                <div class="form-grid">

                    <div class="form-group">
                        <label for="kategori_id">Kategori</label>
                        <select id="kategori_id" name="kategori_id">
                            <option value="">-- Pilih kategori --</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ $kategori->id }}">{{ $kategori->nama }}</option>
                            @endforeach
                        </select>
                        <small class="field-error" id="error-kategori_id"></small>
                    </div>

                    <div class="form-group">
                        <label for="judul">Judul</label>
                        <input type="text" id="judul" name="judul" placeholder="Judul buku">
                        <small class="field-error" id="error-judul"></small>
                    </div>

                    <div class="form-group">
                        <label for="penulis">Penulis</label>
                        <input type="text" id="penulis" name="penulis" placeholder="Nama penulis">
                        <small class="field-error" id="error-penulis"></small>
                    </div>

                    <div class="form-group">
                        <label for="tahun_terbit">Tahun Terbit</label>
                        <input type="number" id="tahun_terbit" name="tahun_terbit" placeholder="Contoh: 2024">
                        <small class="field-error" id="error-tahun_terbit"></small>
                    </div>

                </div>

                <div class="form-actions">
                    <button type="submit" id="tombol-simpan" class="button">Tambah Buku</button>
                    <button type="button" id="tombol-batal" class="button button-secondary" hidden>Batal</button>
                </div>

            </form>

        </section>

        <div class="table-wrap">
            <table class="table-buku">
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
                <tbody id="tbody-buku">
                    <tr><td colspan="6" class="info-row">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>

        <div class="pagination-bar">
            <button type="button" id="tombol-sebelumnya" class="button button-secondary" disabled>&laquo; Sebelumnya</button>
            <span id="info-halaman"></span>
            <button type="button" id="tombol-berikutnya" class="button button-secondary" disabled>Berikutnya &raquo;</button>
        </div>

    </div>

    <script src="{{ asset('js/buku.js') }}"></script>

@endsection