@extends('layouts.app')

@section('title', 'Tambah Buku')

@section('content')

<div class="page-title"><h2>Tambah Buku</h2></div>

@include('buku._form', ['action' => route('buku.store'), 'tombol' => 'Tambah Buku'])

@endsection
