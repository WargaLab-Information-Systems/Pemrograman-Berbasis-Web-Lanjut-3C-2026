@extends('layouts.main')

@section('content')
    <div class="container">
        <h2 class="page-title">Daftar Buku</h2>
        <div class="grid-buku">
            @foreach($buku as $item)
                <x-buku-card :buku="$item" />
            @endforeach
        </div>
    </div>
@endsection