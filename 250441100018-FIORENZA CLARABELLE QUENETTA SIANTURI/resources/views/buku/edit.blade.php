@extends('layouts.app')

@section('title', 'Ubah Buku')

@section('content')

<div class="page-title"><h2>Ubah Buku</h2></div>

@include('buku._form', ['action' => route('buku.update', $buku), 'tombol' => 'Simpan Perubahan'])

@endsection
