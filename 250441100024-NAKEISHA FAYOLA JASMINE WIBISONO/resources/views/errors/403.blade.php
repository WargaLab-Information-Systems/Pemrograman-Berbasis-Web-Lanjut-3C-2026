<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>403 - Akses Ditolak</title>
    <style>
        body { font-family: "Segoe UI", Roboto, Arial, sans-serif; background: #faf7f2; margin: 0; display: flex; min-height: 100vh; align-items: center; justify-content: center; color: #2b2b2b; }
        .box { background: #fff; padding: 32px 40px; border-radius: 10px; box-shadow: 0 1px 4px rgba(0,0,0,.12); text-align: center; max-width: 420px; border-top: 6px solid #6d4c2f; }
        h1 { font-size: 56px; margin: 0; color: #6d4c2f; }
        h2 { margin: 8px 0 12px; }
        p { color: #7a7a7a; }
        a { display: inline-block; margin-top: 12px; padding: 10px 18px; background: #6d4c2f; color: #fff; text-decoration: none; border-radius: 6px; font-weight: 600; }
        a:hover { background: #55371f; }
    </style>
</head>
<body>
    <div class="box">
        <h1>403</h1>
        <h2>Akses Ditolak</h2>
        <p>Anda tidak memiliki hak akses untuk membuka halaman atau menjalankan aksi ini.</p>
        @auth
            <a href="{{ route('dashboard') }}">Kembali ke Dashboard</a>
        @else
            <a href="{{ route('login') }}">Login</a>
        @endauth
    </div>
</body>
</html>
