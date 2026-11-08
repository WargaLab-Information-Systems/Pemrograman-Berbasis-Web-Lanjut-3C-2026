{{-- Menu navigasi, ditampilkan sesuai permission pengguna lewat direktif @can --}}
<x-dynamic-component :component="$komponen" :href="route('dashboard')"
    :active="request()->routeIs('dashboard', 'admin.dashboard', 'petugas.dashboard', 'siswa.dashboard')">
    Dashboard
</x-dynamic-component>

@can('lihat-daftar-buku')
    <x-dynamic-component :component="$komponen" :href="route('buku.index')" :active="request()->routeIs('buku.*')">
        Daftar Buku
    </x-dynamic-component>
@endcan

@can('kelola-data-buku')
    <x-dynamic-component :component="$komponen" :href="route('kelola.buku')" :active="request()->routeIs('kelola.buku')">
        Kelola Buku
    </x-dynamic-component>
@endcan

@can('kelola-peminjaman')
    <x-dynamic-component :component="$komponen" :href="route('peminjaman.index')" :active="request()->routeIs('peminjaman.index')">
        Peminjaman
    </x-dynamic-component>
@endcan

@can('kelola-pengembalian')
    <x-dynamic-component :component="$komponen" :href="route('pengembalian.index')" :active="request()->routeIs('pengembalian.*')">
        Pengembalian
    </x-dynamic-component>
@endcan

@can('lihat-laporan-perpustakaan')
    <x-dynamic-component :component="$komponen" :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
        Laporan
    </x-dynamic-component>
@endcan

@can('kelola-data-pengguna')
    <x-dynamic-component :component="$komponen" :href="route('pengguna.index')" :active="request()->routeIs('pengguna.*')">
        Pengguna
    </x-dynamic-component>
@endcan

@can('lihat-status-peminjaman-sendiri')
    <x-dynamic-component :component="$komponen" :href="route('peminjaman.saya')" :active="request()->routeIs('peminjaman.saya')">
        Peminjaman Saya
    </x-dynamic-component>
@endcan

@can('lihat-riwayat-peminjaman-sendiri')
    <x-dynamic-component :component="$komponen" :href="route('riwayat.saya')" :active="request()->routeIs('riwayat.*')">
        Riwayat
    </x-dynamic-component>
@endcan
