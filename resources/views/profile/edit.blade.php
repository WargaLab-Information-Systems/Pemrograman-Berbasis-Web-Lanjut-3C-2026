@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')

<div class="page-title">
    <h2>Profil Saya</h2>
    <p>Kelola informasi akun dan kata sandi Anda.</p>
</div>

<div class="card card-narrow">
    @include('profile.partials.update-profile-information-form')
</div>

@can('ubah profil sendiri')
    <div class="card card-narrow">
        @include('profile.partials.update-password-form')
    </div>
@endcan

@endsection
