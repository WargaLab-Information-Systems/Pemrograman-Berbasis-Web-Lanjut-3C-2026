<style>
    .nav-header-modul4 {
        background-color: #2c3e50; /* Warna biru gelap atas */
        color: white;
        text-align: center;
        padding: 20px 0;
    }
    .nav-header-modul4 h1 { font-size: 1.1rem; margin: 0 0 5px 0; font-weight: normal; }
    .nav-header-modul4 h2 { font-size: 1rem; margin: 0; font-weight: normal; }

    .navbar-modul4 {
        background-color: #1a252f; /* Warna biru lebih gelap bawah */
        padding: 15px 30px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }
    
    .nav-links-center {
        display: flex;
        gap: 15px;
        margin: 0 auto; /* Memaksa menu berada di tengah */
    }

    .navbar-modul4 a {
        color: white;
        text-decoration: none;
        font-weight: 500;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 0.95rem;
        transition: 0.2s;
    }
    
    .navbar-modul4 a:hover { background-color: rgba(52, 152, 219, 0.2); }
    .navbar-modul4 a.active { 
        background-color: #3498db; 
        border: 1px solid white; /* Border putih untuk menu aktif sesuai referensi */
    }

    /* Memisahkan Log Out ke sudut kanan */
    .logout-container { margin-left: auto; }
    
    .btn-logout {
        background-color: #e74c3c;
        color: white;
        padding: 8px 16px;
        border: none;
        border-radius: 4px;
        font-size: 0.95rem;
        font-weight: 500;
        cursor: pointer;
    }
    .btn-logout:hover { background-color: #c0392b; }
</style>

<!-- Judul Header Atas -->
<div class="nav-header-modul4">
    <h1>Perpustakaan Digital</h1>
    <h2>Sistem Informasi Perpustakaan Digital</h2>
</div>

<!-- Navigasi Utama -->
<div class="navbar-modul4">
    
    <!-- Grup Menu di Tengah -->
    <div class="nav-links-center">
        <!-- Dashboard Siswa -->
        <a href="{{ route('siswa.dashboard') }}" class="{{ request()->routeIs('siswa.dashboard') ? 'active' : '' }}">Dashboard Siswa</a>

        <!-- Daftar Koleksi Buku -->
        <a href="{{ route('buku.index') }}" class="{{ request()->routeIs('buku.*') ? 'active' : '' }}">Koleksi Buku</a>

        <!-- Riwayat Peminjaman -->
        <a href="{{ route('riwayat') }}" class="{{ request()->routeIs('riwayat') ? 'active' : '' }}">Riwayat Peminjaman</a>

        <!-- Profil Saya -->
        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">Profil Saya</a>
    </div>

    <!-- Tombol Log Out di Kanan -->
    <div class="logout-container">
        <form method="POST" action="{{ route('logout') }}" style="display: inline;">
            @csrf
            <button type="submit" class="btn-logout">Log Out</button>
        </form>
    </div>
</div>