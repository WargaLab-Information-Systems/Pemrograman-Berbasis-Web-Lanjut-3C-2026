@extends('layouts.main')

@section('content')
<div class="container-detail">
    @if($buku)
        {{-- Tampilan jika data buku DITEMUKAN --}}
        <div class="detail-card">
            <div class="detail-layout">
                
                {{-- Sampul Buku --}}
                <img src="{{ asset($buku['gambar']) }}" alt="{{ $buku['judul'] }}" class="detail-img">

                {{-- Informasi Detail --}}
                <div class="detail-info">
                    <div>
                        <span class="tag-kategori">{{ $buku['kategori'] }}</span>
                    </div>
                    <h2>{{ $buku['judul'] }}</h2>
                    <hr>
                    <p class="card-info-text"><strong>ID Buku:</strong> <code class="id-buku">{{ $buku['id'] }}</code></p>
                    <p class="card-info-text"><strong>Penulis:</strong> {{ $buku['penulis'] }}</p>
                    <p class="card-info-text"><strong>Tahun Terbit:</strong> {{ $buku['tahun_terbit'] }}</p>
                    
                    @if(!empty($buku['deskripsi']))
                        <p class="card-info-text" style="margin-top: 0.5rem;"><strong>Deskripsi:</strong> {{ $buku['deskripsi'] }}</p>
                    @endif

                    <div style="margin-top: 1.25rem;">
                        <a href="{{ route('buku.index') }}" class="btn-back">
                            &larr; Kembali
                        </a>
                    </div>
                </div>

            </div>
        </div>
    @else
        {{-- Tampilan jika data buku TIDAK DITEMUKAN --}}
        <div class="detail-card">
            <h2 style="color: #ef4444; margin-bottom: 0.5rem;">Buku Tidak Ditemukan</h2>
            <p style="color: #64748b; margin-bottom: 1.25rem;">Maaf, data buku yang kamu cari tidak ada atau kode ID salah.</p>
            <div>
                <a href="{{ route('buku.index') }}" class="btn-back">
                    &larr; Kembali ke Daftar Buku
                </a>
            </div>
        </div>
    @endif
</div>
@endsection