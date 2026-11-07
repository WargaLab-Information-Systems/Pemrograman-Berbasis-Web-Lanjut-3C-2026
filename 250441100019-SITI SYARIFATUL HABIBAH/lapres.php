Php -v	# harus menampilkan PHP 8.4.x
Composer   -v	# memastikan Composer   terpasang
node -v	# harus menampilkan v20.x atau lebih baru
npm -v	


# membuat project Laravel 13 bernama praktikum1
Composer   create-project laravel/laravel:^13.0 praktikum1
# masuk ke direktori project
cd praktikum1
# memasang dependensi front-end
npm install
# menjalankan server pengembangan
php artisan serve


# berkas .env
APP_NAME="Praktikum Laravel"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=db_praktikum1
DB_USERNAME=root
DB_PASSWORD=

php artisan config:clear
php artisan migrate # menguji koneksi; tabel bawaan akan terbentuk


// parameter wajib
Route::get('/mahasiswa/{nim}', function ($nim) {
return 'NIM mahasiswa: ' . $nim;
});
// parameter opsional dengan nilai bawaan
Route::get('/profil/{nama?}', function ($nama = 'Tamu') {
return 'Selamat datang, ' . $nama;
});


Route::get('/mahasiswa', [MahasiswaController::class, 'index'])
->name('mahasiswa.index');
Pemanggilan pada Blade  maupun controller:
<!-- di dalam view Blade  -->
<a href="{{ route('mahasiswa.index') }}">Daftar Mahasiswa</a>
// di dalam controller
return redirect()->route('mahasiswa.index');


Route::prefix('admin')
->name('admin.')
->Middleware ('auth')
->group(function () {

     Route::get('/dashboard', [AdminController::class, 'dashboard'])
     ->name('dashboard'); // URL: /admin/dashboard → nama:

admin.dashboard

    Route::get('/mahasiswa', [AdminController::class, 'mahasiswa'])
        ->name('mahasiswa'); // URL: /admin/mahasiswa → nama:

admin.mahasiswa
});

php artisan make:controller MahasiswaController

// app/Http/Controllers/MahasiswaController.php
namespace App\Http\Controllers;
class MahasiswaController extends Controller
{
    public function index()
    {
        $nama = 'Sinta Maharani';
        $daftar = ['Basis Data', 'Pemrograman Web ', 'Algoritma'];

        return view('mahasiswa.index', [
            'nama' => $nama,
            'daftar' => $daftar,
       ]);
   }
}

php artisan make:Middleware  CekUsia

// app/Http/Middleware /CekUsia.php
public function handle(Request $request, Closure $next): Response
{
    if ($request->usia < 17) {
        return redirect('/beranda');
}

    return $next($request); // lanjutkan ke proses berikutnya
}


->withMiddleware (function (Middleware  $Middleware ) {
$Middleware ->alias([
'cek.usia' => \App\Http\Middleware \CekUsia::class,
]);
})


Route::get('/khusus', [HalamanController::class, 'khusus'])
->Middleware ('cek.usia');

use Illuminate\Routing\Attributes\Controllers\Middleware ;
#[Middleware ('cek.usia')]
class HalamanController
{
#[Middleware ('auth')]
public function khusus()
{
// hanya dapat diakses bila kedua Middleware  lolos
}
}


<!-- resources/views/mahasiswa/index.Blade .php -->
<h1>Selamat datang, {{ $nama }}</h1>

@if (count($daftar) > 0)
    <ul>
       @foreach ($daftar as $matkul)
           <li>{{ $loop->iteration }}. {{ $matkul }}</li>
       @endforeach
    </ul>
@else
    <p>Belum ada mata kuliah yang diambil.</p>
@endif

Parent file — resources/views/layouts/app.Blade .php:

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>@yield('judul', 'Aplikasi Saya')</title>
</head>
<body>
    @include('partials.navbar')

    <main>
         @yield('konten')
    </main>

    <footer>© 2026 Sistem Informasi</footer>
</body>
</html>

Child file — resources/views/beranda.Blade .php:

@extends('layouts.app')
@section('judul', 'Halaman Beranda')
@section('konten')
<h1>Beranda</h1>
<p>Ini adalah isi halaman beranda.</p>
@endsection

# component dengan kelas
php artisan make:component Kartu

# component anonim (hanya berkas view)
php artisan make:component Kartu --view

@props(['judul' => 'Tanpa Judul'])

<div class="kartu">
    <h3>{{ $judul }}</h3>

    <div class="isi">
         {{ $slot }}          {{-- slot utama --}}
    </div>

    <div class="kaki">
{{ $footer ?? '' }}           {{-- slot bernama --}}
    </div>
</div>

<x-kartu judul="Data Mahasiswa">
     <p>Nama: Sinta Maharani</p>
     <p>NIM : 2410010001</p>

     <x-slot:footer>
       <a href="{{ route('mahasiswa.index') }}">Lihat semua</a>
     </x-slot:footer>
</x-kartu>

# memasang dependensi front-end
npm install

# mode pengembangan (hot reload), jalankan di terminal terpisah
npm run dev

# kompilasi untuk produksi
npm run build

<head>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
