<nav x-data="{ open: false }" style="background-color: #0284c7; border-bottom: 1px solid #0369a1; color: #ffffff; position: relative; z-index: 50; shadow: 0 4px 6px -1px rgba(0,0,0,0.1);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current" style="color: #ffffff;" />
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" style="color: #ffffff; font-weight: 600;">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    <x-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" style="color: #ffffff; font-weight: 600;">
                        {{ __('Daftar Buku') }}
                    </x-nav-link>

                    @hasrole('Siswa')
                        <x-nav-link :href="route('peminjaman.riwayat')" :active="request()->routeIs('peminjaman.riwayat')" style="color: #ffffff; font-weight: 600;">
                            {{ __('Riwayat Saya') }}
                        </x-nav-link>
                    @endhasrole

                    @hasrole('Petugas|Admin')
                        <x-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')" style="color: #ffffff; font-weight: 600;">
                            {{ __('Kelola Peminjaman') }}
                        </x-nav-link>

                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.index')" style="color: #ffffff; font-weight: 600;">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endhasrole

                    @hasrole('Admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" style="color: #ffffff; font-weight: 600;">
                            {{ __('Kelola Pengguna') }}
                        </x-nav-link>
                    @endhasrole

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md" style="background-color: #0369a1; color: #ffffff;">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" style="color: #ffffff;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md" style="color: #ffffff;">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden" style="background-color: #0369a1; color: #ffffff;">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" style="color: #ffffff;">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('buku.index')" :active="request()->routeIs('buku.*')" style="color: #ffffff;">
                {{ __('Daftar Buku') }}
            </x-responsive-nav-link>

            @if(auth()->user()->hasRole(['Siswa', 'siswa']) || auth()->user()->can('Melihat riwayat peminjaman sendiri') || auth()->user()->can('lihat riwayat peminjaman sendiri') || !auth()->user()->hasRole(['Admin', 'admin', 'Petugas', 'petugas']))
                <x-responsive-nav-link :href="route('peminjaman.riwayat')" :active="request()->routeIs('peminjaman.riwayat')" style="color: #ffffff;">
                    {{ __('Riwayat Saya') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasRole(['Admin', 'admin', 'Petugas', 'petugas']) || auth()->user()->can('Kelola peminjaman') || auth()->user()->can('kelola peminjaman'))
                <x-responsive-nav-link :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')" style="color: #ffffff;">
                    {{ __('Kelola Transaksi') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasRole(['Admin', 'admin', 'Petugas', 'petugas']) || auth()->user()->can('Lihat laporan perpustakaan') || auth()->user()->can('lihat laporan perpustakaan'))
                <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')" style="color: #ffffff;">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @endif

            @if(auth()->user()->hasRole(['Admin', 'admin']) || auth()->user()->can('Kelola data pengguna') || auth()->user()->can('kelola data pengguna'))
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')" style="color: #ffffff;">
                    {{ __('Kelola Pengguna') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t" style="border-color: #075985;">
            <div class="px-4">
                <div class="font-medium text-base" style="color: #ffffff;">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm" style="color: #e0f2fe;">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" style="color: #ffffff;">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();" style="color: #ffffff;">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>