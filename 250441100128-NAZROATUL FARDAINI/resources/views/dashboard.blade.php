<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Utama') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- 1. TAMPILAN DASHBOARD SISWA (GAYA MODUL 4) --}}
            @hasrole('Siswa')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-xl mb-2" style="color: #1e293b;">Selamat Datang, {{ Auth::user()->name }}!</h3>
                    <p class="text-gray-600 mb-6">Anda masuk sebagai <span class="font-semibold text-blue-600">Siswa</span>. Berikut ringkasan aktivitas akun Anda di sistem perpustakaan.</p>
                </div>
            @endhasrole

            {{-- 2. TAMPILAN DASHBOARD ADMIN (GAYA MODUL 4) --}}
            @hasrole('Admin')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-xl mb-2" style="color: #1e293b;">Dashboard Administrator</h3>
                    <p class="text-gray-600 mb-6">Halo <span class="font-semibold text-purple-600">{{ Auth::user()->name }}</span>, Anda memiliki akses penuh ke seluruh manajemen sistem.</p>

                </div>
            @endhasrole

            {{-- 3. TAMPILAN DASHBOARD PETUGAS (GAYA MODUL 4) --}}
            @hasrole('Petugas')
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="font-bold text-xl mb-2" style="color: #1e293b;">Dashboard Petugas Perpustakaan</h3>
                    <p class="text-gray-600 mb-6">Selamat bertugas, <span class="font-semibold text-green-600">{{ Auth::user()->name }}</span>. Silakan kelola operasional harian perpustakaan.</p>

                </div>
            @endhasrole

        </div>
    </div>
</x-app-layout>