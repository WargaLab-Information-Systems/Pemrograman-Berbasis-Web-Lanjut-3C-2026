@php
    // Satu daftar menu dipakai untuk tampilan desktop dan ponsel.
    // 'can' = menu hanya tampil jika pengguna punya salah satu permission ini (null = semua pengguna).
    $menu = [
        ['route' => 'home',                 'label' => 'Beranda',      'can' => null,                                                   'aktif' => ['home']],
        ['route' => 'dashboard',            'label' => 'Dashboard',    'can' => ['akses dashboard admin', 'akses dashboard petugas'],   'aktif' => ['dashboard', 'admin.dashboard', 'petugas.dashboard']],
        ['route' => 'buku.index',           'label' => auth()->user()->can('kelola-buku') ? 'Kelola Buku' : 'Daftar Buku',   // Soal 3: Gate
                                                                       'can' => ['lihat daftar buku'],                                  'aktif' => ['buku.*']],
        ['route' => 'kelola.peminjaman',    'label' => 'Peminjaman',   'can' => ['kelola peminjaman'],                                  'aktif' => ['kelola.peminjaman*']],
        ['route' => 'kelola.pengembalian',  'label' => 'Pengembalian', 'can' => ['kelola pengembalian'],                                'aktif' => ['kelola.pengembalian*']],
        ['route' => 'laporan.index',        'label' => 'Laporan',      'can' => ['lihat laporan perpustakaan'],                         'aktif' => ['laporan.*']],
        ['route' => 'admin.pengguna.index', 'label' => 'Pengguna',     'can' => ['kelola data pengguna'],                               'aktif' => ['admin.pengguna.*']],
    ];
@endphp

<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links (desktop) -->
                <div class="hidden space-x-6 lg:-my-px lg:ms-8 lg:flex">
                    @foreach ($menu as $item)
                        @if (is_null($item['can']))
                            <x-nav-link :href="route($item['route'])" :active="request()->routeIs(...$item['aktif'])">{{ $item['label'] }}</x-nav-link>
                        @else
                            @canany($item['can'])
                                <x-nav-link :href="route($item['route'])" :active="request()->routeIs(...$item['aktif'])">{{ $item['label'] }}</x-nav-link>
                            @endcanany
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden lg:flex lg:items-center lg:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            {{-- Label role: ditampilkan dengan direktif @role --}}
                            @role('admin')   <span class="ms-2 rounded bg-indigo-100 px-1.5 py-0.5 text-xs font-semibold text-indigo-800">Admin</span> @endrole
                            @role('petugas') <span class="ms-2 rounded bg-teal-100 px-1.5 py-0.5 text-xs font-semibold text-teal-800">Petugas</span> @endrole
                            @role('siswa')   <span class="ms-2 rounded bg-amber-100 px-1.5 py-0.5 text-xs font-semibold text-amber-800">Siswa</span> @endrole

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        @can('melihat status peminjaman sendiri')
                            <x-dropdown-link :href="route('peminjaman.status')">Peminjaman Saya</x-dropdown-link>
                        @endcan

                        @can('melihat riwayat peminjaman sendiri')
                            <x-dropdown-link :href="route('peminjaman.riwayat')">Riwayat Peminjaman</x-dropdown-link>
                        @endcan

                        @can('lihat profil sendiri')
                            <x-dropdown-link :href="route('profile.edit')">Profil Saya</x-dropdown-link>
                        @endcan

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center lg:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden lg:hidden">
        <div class="pt-2 pb-3 space-y-1">
            @foreach ($menu as $item)
                @if (is_null($item['can']))
                    <x-responsive-nav-link :href="route($item['route'])" :active="request()->routeIs(...$item['aktif'])">{{ $item['label'] }}</x-responsive-nav-link>
                @else
                    @canany($item['can'])
                        <x-responsive-nav-link :href="route($item['route'])" :active="request()->routeIs(...$item['aktif'])">{{ $item['label'] }}</x-responsive-nav-link>
                    @endcanany
                @endif
            @endforeach
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                @can('melihat status peminjaman sendiri')
                    <x-responsive-nav-link :href="route('peminjaman.status')">Peminjaman Saya</x-responsive-nav-link>
                @endcan

                @can('melihat riwayat peminjaman sendiri')
                    <x-responsive-nav-link :href="route('peminjaman.riwayat')">Riwayat Peminjaman</x-responsive-nav-link>
                @endcan

                @can('lihat profil sendiri')
                    <x-responsive-nav-link :href="route('profile.edit')">Profil Saya</x-responsive-nav-link>
                @endcan

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        Keluar
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
