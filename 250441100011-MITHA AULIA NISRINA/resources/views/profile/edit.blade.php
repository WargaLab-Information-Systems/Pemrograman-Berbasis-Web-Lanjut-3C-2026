@extends('layouts.app')

@section('title', 'Profil Saya - KATAMITHA')

@section('content')

<div class="profile-page">

    {{-- =========================================
         HEADER PROFIL
         ========================================= --}}
    <div class="section-heading">

        <span>AKUN SAYA</span>

        <h2>Profil Saya</h2>

        <p>
            Lihat dan perbarui informasi akun yang sedang kamu gunakan
            di perpustakaan digital KATAMITHA.
        </p>

        <div class="book-list-meta">

            <div>
                <strong>{{ auth()->user()->name }}</strong>
                <span>NAMA PENGGUNA</span>
            </div>

            <div>
                <strong>
                    {{ auth()->user()->getRoleNames()->first() ?? '-' }}
                </strong>
                <span>ROLE</span>
            </div>

            <div>
                <strong>AKTIF</strong>
                <span>STATUS AKUN</span>
            </div>

        </div>

    </div>


    {{-- =========================================
         INFORMASI AKUN
         ========================================= --}}
    <div class="profile-section">

        <div class="section-heading">

            <span>INFORMASI AKUN</span>

            <h2>Data Profil</h2>

            <p>
                Perbarui nama dan alamat email yang digunakan
                pada akun perpustakaan.
            </p>

        </div>

        <div class="profile-form-wrapper">

            @include('profile.partials.update-profile-information-form')

        </div>

    </div>


    {{-- =========================================
         UBAH PASSWORD
         ========================================= --}}
    <div class="profile-section">

        <div class="section-heading">

            <span>KEAMANAN AKUN</span>

            <h2>Ubah Password</h2>

            <p>
                Gunakan password yang kuat untuk menjaga keamanan
                akun perpustakaan kamu.
            </p>

        </div>

        <div class="profile-form-wrapper">

            @include('profile.partials.update-password-form')

        </div>

    </div>

</div>

@endsection