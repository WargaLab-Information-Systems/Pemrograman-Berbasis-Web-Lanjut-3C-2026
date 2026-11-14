<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-slate-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="dash-bg">
        <div class="dash-container">
            
            <div class="dash-hero">
                <div>
                    <div style="margin-bottom: 8px;">
                        <span class="dash-hero-tag">Sistem Informasi Perpustakaan</span>
                    </div>
                    <h1 class="dash-hero-title">
                        Selamat Datang, {{ Auth::user()->name }}
                    </h1>
                    <p class="dash-hero-desc">
                        @role('admin')
                            Anda memiliki hak akses penuh sebagai <strong>Administrator</strong>. Kelola data pengguna, buku, peminjaman, serta pantau laporan aktivitas sistem di sini.
                        @endrole
                        @role('petugas')
                            Anda berada di mode <strong>Petugas Perpustakaan</strong>. Kelola transaksi peminjaman, pengembalian buku, dan kelengkapan laporan operasional.
                        @endrole
                        @role('siswa')
                            Selamat menjelajahi koleksi buku perpustakaan digital kami. Temukan buku favoritmu dan pantau status pinjamanmu dengan mudah.
                        @endrole
                    </p>
                </div>
            </div>

            <div class="dash-card">
                <h3 class="dash-card-title">Informasi Hak Akses & Status Sesi</h3>
                <div class="dash-grid-3">
                    <div class="dash-info-box">
                        <p class="dash-info-label">Role Pengguna</p>
                        <p class="dash-info-val">
                            @role('admin') Administrator @endrole
                            @role('petugas') Petugas Perpustakaan @endrole
                            @role('siswa') Siswa / Anggota @endrole
                        </p>
                    </div>

                    <div class="dash-info-box">
                        <p class="dash-info-label">Email Terdaftar</p>
                        <p class="dash-info-email">
                            {{ Auth::user()->email }}
                        </p>
                    </div>

                    <div class="dash-info-box">
                        <p class="dash-info-label">Status Sesi</p>
                        <p class="dash-info-status">Terautentikasi Aktif</p>
                    </div>
                </div>
            </div>

            <div class="dash-card">
                <h3 class="dash-card-title">Fitur Utama</h3>
                <div class="dash-grid-nav">
                    
                    <a href="{{ Route::has('buku.index') ? route('buku.index') : url('/buku') }}" class="dash-nav-item">
                        <h4 class="dash-nav-title">Daftar Buku</h4>
                        <p class="dash-nav-desc">Lihat dan jelajahi seluruh koleksi buku.</p>
                    </a>

                    @role('admin')
                        <a href="{{ Route::has('pengguna.index') ? route('pengguna.index') : url('/admin/users') }}" class="dash-nav-item">
                            <h4 class="dash-nav-title">Kelola Data Pengguna</h4>
                            <p class="dash-nav-desc">Atur role admin, petugas, dan siswa.</p>
                        </a>
                    @endrole

                    @hasanyrole('admin|petugas')
                        <a href="{{ Route::has('peminjaman.index') ? route('peminjaman.index') : url('/peminjaman') }}" class="dash-nav-item">
                            <h4 class="dash-nav-title">Transaksi Peminjaman</h4>
                            <p class="dash-nav-desc">Konfirmasi & update status pinjaman.</p>
                        </a>

                        <a href="{{ Route::has('laporan.index') ? route('laporan.index') : url('/laporan') }}" class="dash-nav-item">
                            <h4 class="dash-nav-title">Laporan Perpustakaan</h4>
                            <p class="dash-nav-desc">Rekap rekapitulasi data peminjaman.</p>
                        </a>
                    @endhasanyrole

                    @role('siswa')
                        <a href="{{ Route::has('siswa.riwayat') ? route('siswa.riwayat') : url('/riwayat-saya') }}" class="dash-nav-item">
                            <h4 class="dash-nav-title">Riwayat Saya</h4>
                            <p class="dash-nav-desc">Pantau buku yang sedang kamu pinjam.</p>
                        </a>
                    @endrole

                </div>
            </div>

        </div>
    </div>
</x-app-layout>