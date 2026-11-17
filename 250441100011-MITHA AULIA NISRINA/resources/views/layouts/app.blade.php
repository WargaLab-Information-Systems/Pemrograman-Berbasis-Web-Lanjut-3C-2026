<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'KATAMITHA')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    {{-- ========================================================= --}}
    {{-- HEADER --}}
    {{-- ========================================================= --}}

    <header class="site-header">

        <div class="header-content">

            <a href="{{ route('home') }}" class="brand">

                <div class="brand-text">

                    <span class="brand-label">
                        DIGITAL LIBRARY
                    </span>

                    <h1>
                        KATAMITHA
                    </h1>

                    <p>
                        Tempat Kata Menjadi Cerita
                    </p>

                </div>

            </a>

        </div>

    </header>


    {{-- ========================================================= --}}
    {{-- NAVBAR --}}
    {{-- ========================================================= --}}

    <nav class="navbar">

        <div class="nav-content">

            <div class="nav-title">

                <div>
                    <small>KATAMITHA</small>
                    <strong>Perpustakaan Digital</strong>
                </div>

            </div>


            {{-- Tombol hamburger --}}
            <button
                type="button"
                class="nav-toggle"
                id="navToggle"
                aria-label="Buka menu navigasi"
                aria-expanded="false"
                aria-controls="navLinks"
            >

                <span></span>
                <span></span>
                <span></span>

            </button>


            {{-- Menu navigasi --}}
            <div class="nav-links" id="navLinks">

                <a
                    href="{{ route('home') }}"
                    class="{{ request()->routeIs('home') ? 'active' : '' }}"
                >
                    Beranda
                </a>


                <a
                    href="{{ route('buku.index') }}"
                    class="{{ request()->routeIs('buku.*') ? 'active' : '' }}"
                >
                    Daftar Buku
                </a>


                @auth

                    @can('kelola data buku')

                        <a
                            href="{{ route('kelola-buku.index') }}"
                            class="{{ request()->routeIs('kelola-buku.*') ? 'active' : '' }}"
                        >
                            Kelola Buku
                        </a>

                    @endcan


                    @role('admin')

                        <a
                            href="{{ route('admin.dashboard') }}"
                            class="{{ request()->routeIs('admin.*') ? 'active' : '' }}"
                        >
                            Dashboard Admin
                        </a>

                    @endrole


                    @role('petugas')

                        <a
                            href="{{ route('petugas.dashboard') }}"
                            class="{{ request()->routeIs('petugas.*') ? 'active' : '' }}"
                        >
                            Dashboard Petugas
                        </a>

                    @endrole


                    @can('melihat riwayat peminjaman sendiri')

                        <a
                            href="{{ route('riwayat-peminjaman.index') }}"
                            class="{{ request()->routeIs('riwayat-peminjaman.*') ? 'active' : '' }}"
                        >
                            Riwayat Peminjaman
                        </a>

                    @endcan


                    @can('lihat profil sendiri')

                        <a
                            href="{{ route('profile.edit') }}"
                            class="{{ request()->routeIs('profile.*') ? 'active' : '' }}"
                        >
                            Profil
                        </a>

                    @endcan


                    <div class="nav-user">

                        <span class="nav-user-name">
                            {{ Auth::user()->name }}
                        </span>


                        <button
                            type="button"
                            class="nav-logout-button"
                            onclick="openLogoutModal()"
                        >
                            Logout
                        </button>

                    </div>

                @else

                    <a
                        href="{{ route('login') }}"
                        class="{{ request()->routeIs('login') ? 'active' : '' }}"
                    >
                        Masuk
                    </a>


                    <a
                        href="{{ route('register') }}"
                        class="{{ request()->routeIs('register') ? 'active' : '' }}"
                    >
                        Daftar
                    </a>

                @endauth

            </div>

        </div>

    </nav>


    {{-- ========================================================= --}}
    {{-- CONTENT --}}
    {{-- ========================================================= --}}

    <main class="main-content">

        @yield('content')

    </main>


    {{-- ========================================================= --}}
    {{-- FOOTER --}}
    {{-- ========================================================= --}}

    <footer class="site-footer">

        <div class="footer-content">

            <div class="footer-brand">

                <a href="{{ route('home') }}" class="footer-logo">
                    KATAMITHA
                </a>

                <p class="footer-tagline">
                    Tempat Kata Menjadi Cerita
                </p>

                <p class="footer-description">
                    KATAMITHA merupakan perpustakaan digital yang
                    menghadirkan berbagai koleksi bacaan untuk memperluas
                    wawasan, menemukan perspektif baru, dan menemani
                    perjalanan membaca.
                </p>

            </div>


            <div class="footer-column">

                <h4>
                    KOLEKSI
                </h4>

                <a href="{{ route('buku.index', ['category' => 'Sastra & Fiksi']) }}">
                    Sastra & Fiksi
                </a>

                <a href="{{ route('buku.index', ['category' => 'Pengembangan Diri']) }}">
                    Pengembangan Diri
                </a>

                <a href="{{ route('buku.index', ['category' => 'Sejarah & Sosial']) }}">
                    Sejarah & Sosial
                </a>

                <a href="{{ route('buku.index', ['category' => 'Pendidikan']) }}">
                    Pendidikan
                </a>

                <a href="{{ route('buku.index', ['category' => 'Teknologi & Digital']) }}">
                    Teknologi & Digital
                </a>

                <a href="{{ route('buku.index', ['category' => 'Sains & Pengetahuan']) }}">
                    Sains & Pengetahuan
                </a>

            </div>


            <div class="footer-column">

                <h4>
                    NAVIGASI
                </h4>

                <a href="{{ route('home') }}">
                    Beranda
                </a>

                <a href="{{ route('buku.index') }}">
                    Daftar Buku
                </a>


                @guest

                    <a href="{{ route('login') }}">
                        Masuk
                    </a>

                    <a href="{{ route('register') }}">
                        Daftar
                    </a>

                @endguest


                @auth

                    @can('kelola data buku')

                        <a href="{{ route('kelola-buku.index') }}">
                            Kelola Buku
                        </a>

                    @endcan


                    @role('admin')

                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard Admin
                        </a>

                    @endrole


                    @role('petugas')

                        <a href="{{ route('petugas.dashboard') }}">
                            Dashboard Petugas
                        </a>

                    @endrole


                    @can('melihat riwayat peminjaman sendiri')

                        <a href="{{ route('riwayat-peminjaman.index') }}">
                            Riwayat Peminjaman
                        </a>

                    @endcan


                    @can('lihat profil sendiri')

                        <a href="{{ route('profile.edit') }}">
                            Profil
                        </a>

                    @endcan

                @endauth

            </div>


            <div class="footer-column">

                <h4>
                    TENTANG KATAMITHA
                </h4>

                <span>
                    Perpustakaan Digital
                </span>

                <span>
                    Koleksi Bacaan
                </span>

                <span>
                    Ruang Membaca
                </span>

            </div>

        </div>


        <div class="footer-quote">

            <span class="quote-line"></span>

            <p>
                “Setiap halaman menyimpan pengetahuan.
                Setiap kata membuka kemungkinan baru.”
            </p>

            <span class="quote-line"></span>

        </div>


        <div class="footer-bottom">

            <p>
                © 2026 KATAMITHA · Perpustakaan Digital
            </p>

            <span>
                Membaca · Menemukan · Berkembang
            </span>

        </div>

    </footer>


    {{-- ========================================================= --}}
    {{-- MODAL KONFIRMASI LOGOUT --}}
    {{-- ========================================================= --}}

    @auth

        <div
            id="logoutModal"
            class="logout-modal"
            aria-hidden="true"
        >

            <div
                class="logout-modal-overlay"
                onclick="closeLogoutModal()"
            ></div>


            <div
                class="logout-modal-card"
                role="dialog"
                aria-modal="true"
                aria-labelledby="logoutModalTitle"
            >

                <div class="logout-modal-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M10 17l5-5-5-5"/>
                        <path d="M15 12H3"/>
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/>
                    </svg>

                </div>


                <div class="logout-modal-heading">

                    <span>
                        KONFIRMASI LOGOUT
                    </span>

                    <h2 id="logoutModalTitle">
                        Keluar dari Akun?
                    </h2>

                    <p>
                        Pastikan kamu benar-benar ingin mengakhiri sesi
                        akun yang sedang digunakan.
                    </p>

                </div>


                <div class="logout-account-info">

                    <div class="logout-account-avatar">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>

                    <div class="logout-account-detail">

                        <strong>
                            {{ Auth::user()->name }}
                        </strong>

                        <span>
                            {{ ucfirst(Auth::user()->getRoleNames()->first() ?? 'Pengguna') }}
                        </span>

                    </div>

                </div>


                <p class="logout-confirmation-text">

                    Apakah kamu yakin ingin keluar dari akun

                    <strong>
                        {{ Auth::user()->name }}
                    </strong>?

                </p>


                <div class="logout-modal-actions">

                    <button
                        type="button"
                        class="logout-cancel-button"
                        onclick="closeLogoutModal()"
                    >
                        Batal
                    </button>


                    <form
                        method="POST"
                        action="{{ route('logout') }}"
                        class="logout-confirm-form"
                    >

                        @csrf

                        <button
                            type="submit"
                            class="logout-confirm-button"
                        >
                            Ya, Logout
                        </button>

                    </form>

                </div>

            </div>

        </div>

    @endauth


    @stack('scripts')


    {{-- ========================================================= --}}
    {{-- SCRIPT MODAL LOGOUT --}}
    {{-- ========================================================= --}}

    @auth

        <script>

            function openLogoutModal() {

                const modal = document.getElementById('logoutModal');

                if (!modal) {
                    return;
                }

                modal.classList.add('show');
                modal.setAttribute('aria-hidden', 'false');

                document.body.classList.add('logout-modal-open');
            }


            function closeLogoutModal() {

                const modal = document.getElementById('logoutModal');

                if (!modal) {
                    return;
                }

                modal.classList.remove('show');
                modal.setAttribute('aria-hidden', 'true');

                document.body.classList.remove('logout-modal-open');
            }


            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {
                    closeLogoutModal();
                }

            });

        </script>

    @endauth


    {{-- ========================================================= --}}
    {{-- SCRIPT NAVBAR HAMBURGER --}}
    {{-- ========================================================= --}}

    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const navToggle = document.getElementById('navToggle');
            const navLinks = document.getElementById('navLinks');

            if (!navToggle || !navLinks) {
                return;
            }


            /*
             * Membuka dan menutup menu hamburger.
             */
            navToggle.addEventListener('click', function () {

                const isOpen = navLinks.classList.toggle('show');

                navToggle.classList.toggle('active', isOpen);

                navToggle.setAttribute(
                    'aria-expanded',
                    isOpen ? 'true' : 'false'
                );

                navToggle.setAttribute(
                    'aria-label',
                    isOpen
                        ? 'Tutup menu navigasi'
                        : 'Buka menu navigasi'
                );

            });


            /*
             * Tutup menu setelah link navigasi dipilih.
             */
            navLinks.querySelectorAll('a').forEach(function (link) {

                link.addEventListener('click', function () {

                    navLinks.classList.remove('show');
                    navToggle.classList.remove('active');

                    navToggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                    navToggle.setAttribute(
                        'aria-label',
                        'Buka menu navigasi'
                    );

                });

            });


            /*
             * Bersihkan kondisi hamburger ketika layar
             * diperbesar kembali menjadi desktop.
             */
            window.addEventListener('resize', function () {

                if (window.innerWidth > 1100) {

                    navLinks.classList.remove('show');
                    navToggle.classList.remove('active');

                    navToggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                    navToggle.setAttribute(
                        'aria-label',
                        'Buka menu navigasi'
                    );

                }

            });


            /*
             * Tombol Escape juga dapat menutup menu.
             */
            document.addEventListener('keydown', function (event) {

                if (event.key === 'Escape') {

                    navLinks.classList.remove('show');
                    navToggle.classList.remove('active');

                    navToggle.setAttribute(
                        'aria-expanded',
                        'false'
                    );

                    navToggle.setAttribute(
                        'aria-label',
                        'Buka menu navigasi'
                    );

                }

            });

        });

    </script>

</body>

</html>