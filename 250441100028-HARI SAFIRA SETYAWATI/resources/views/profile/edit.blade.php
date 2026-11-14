<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Perpustakaan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .library-page {
            max-width: 1200px;
            margin: 35px auto;
            padding: 25px;
            color: #493728;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .library-header {
            margin-bottom: 25px;
        }

        .library-header h1,
        .form-title,
        .user-name {
            font-family: 'Playfair Display', Georgia, serif;
            color: #654a32;
        }

        .library-header h1 {
            margin: 0 0 8px;
            font-size: 32px;
        }

        .library-header p {
            margin: 0;
            color: #8b7865;
            font-size: 14px;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .profile-grid {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 25px;
        }

        .library-card {
            background: #fffdf9;
            border: 1px solid #eadfce;
            border-radius: 15px;
            padding: 24px;
            margin-bottom: 25px;
            box-shadow: 0 5px 18px rgba(91, 65, 39, 0.06);
        }

        .form-title {
            margin: 0 0 20px;
            font-size: 24px;
        }

        .book-form {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px 24px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            min-width: 0;
        }

        .form-group label {
            font-size: 15px;
            font-weight: 600;
            color: #654a32;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .form-group input {
            width: 100%;
            height: 57px;
            box-sizing: border-box;
            padding: 0 16px;
            border: 1px solid #e3cdb5;
            border-radius: 11px;
            background: #fffdf9;
            color: #654a32;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 15px;
        }

        .form-group input::placeholder {
            color: #8b7865;
            opacity: 1;
        }

        .form-group input:focus {
            outline: none;
            border-color: #987955;
            box-shadow: 0 0 0 3px rgba(152, 121, 85, 0.12);
        }

        .form-actions {
            grid-column: 1 / -1;
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 5px;
        }

        .btn {
            display: inline-block;
            border: none;
            border-radius: 9px;
            padding: 11px 18px;
            cursor: pointer;
            font-family: 'Poppins', Arial, sans-serif;
            font-size: 13px;
            text-decoration: none;
            text-align: center;
            transition: background 0.2s;
        }

        .btn-primary {
            background: #806548;
            color: white;
        }

        .btn-primary:hover {
            background: #654a32;
            color: white;
        }

        .btn-delete {
            background: #a74e40;
            color: white;
        }

        .btn-delete:hover {
            background: #873b30;
            color: white;
        }

        .avatar-box {
            text-align: center;
        }

        .avatar-circle {
            width: 90px;
            height: 90px;
            background: #806548;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            font-weight: bold;
            margin: 0 auto 15px;
            box-shadow: 0 4px 12px rgba(91, 65, 39, 0.15);
            font-family: 'Playfair Display', Georgia, serif;
        }

        .user-name {
            font-size: 20px;
            font-weight: bold;
            margin: 0 0 4px;
        }

        .user-email {
            font-size: 13px;
            color: #8b7865;
            margin: 0 0 15px;
            word-break: break-all;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .category-badge {
            display: inline-block;
            background: #eee2d0;
            color: #765638;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .info-note {
            color: #8b7865;
            font-size: 13px;
            line-height: 1.6;
            margin: 0;
            font-family: 'Poppins', Arial, sans-serif;
        }

        .success-text {
            font-size: 13px;
            color: #38643a;
            font-weight: 600;
            font-family: 'Poppins', Arial, sans-serif;
        }

        @media (max-width: 850px) {
            .profile-grid {
                grid-template-columns: 1fr;
            }

            .book-form {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 600px) {
            .library-page {
                padding: 15px;
                margin: 20px auto;
            }

            .library-header h1 {
                font-size: 26px;
            }

            .library-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body class="font-sans antialiased bg-slate-50">
    <div class="min-h-screen">
        {{-- Navigasi Top Bar --}}
        @include('layouts.navigation')

        <div class="library-page">
            <div class="library-header">
                <h1>Pengaturan Profil</h1>
                <p>Kelola data identitas dan pengaturan keamanan akun Anda.</p>
            </div>

            <div class="profile-grid">
                <div>
                    <div class="library-card avatar-box">
                        <div class="avatar-circle">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <h3 class="user-name">{{ $user->name }}</h3>
                        <p class="user-email">{{ $user->email }}</p>

                        @forelse ($user->getRoleNames() as $role)
                            <span class="category-badge">{{ $role }}</span>
                        @empty
                            <span class="category-badge">Pengguna</span>
                        @endforelse
                    </div>

                    <div class="library-card">
                        <h2 class="form-title" style="font-size: 18px;">Privasi & Keamanan</h2>
                        <p class="info-note">
                            Pastikan kata sandi Anda kombinasi acak. Selalu jaga kerahasiaan akun Anda saat beraktivitas di sistem.
                        </p>
                    </div>
                </div>

                <div>
                    <!-- 1. Informasi Profil -->
                    <div class="library-card">
                        <h2 class="form-title">Informasi Profil</h2>

                        <form method="post" action="{{ route('profile.update') }}" class="book-form">
                            @csrf
                            @method('patch')

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="name">Nama Lengkap</label>
                                <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" placeholder="Masukkan nama lengkap" required autofocus>
                            </div>

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="email">Alamat Email</label>
                                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" placeholder="Masukkan alamat email" required>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                @if (session('status') === 'profile-updated')
                                    <span class="success-text">✓ Tersimpan.</span>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- 2. Perbarui Kata Sandi -->
                    <div class="library-card">
                        <h2 class="form-title">Perbarui Kata Sandi</h2>

                        <form method="post" action="{{ route('password.update') }}" class="book-form">
                            @csrf
                            @method('put')

                            <div class="form-group" style="grid-column: 1 / -1;">
                                <label for="current_password">Kata Sandi Saat Ini</label>
                                <input id="current_password" name="current_password" type="password" placeholder="Masukkan kata sandi saat ini">
                            </div>

                            <div class="form-group">
                                <label for="password">Kata Sandi Baru</label>
                                <input id="password" name="password" type="password" placeholder="Masukkan kata sandi baru">
                            </div>

                            <div class="form-group">
                                <label for="password_confirmation">Konfirmasi Kata Sandi</label>
                                <input id="password_confirmation" name="password_confirmation" type="password" placeholder="Ulangi kata sandi baru">
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Perbarui Kata Sandi</button>
                                @if (session('status') === 'password-updated')
                                    <span class="success-text">✓ Tersimpan.</span>
                                @endif
                            </div>
                        </form>
                    </div>

                    <!-- 3. Hapus Akun -->
                    <div class="library-card">
                        <h2 class="form-title" style="color: #a74e40;">Hapus Akun</h2>
                        <p class="info-note" style="margin-bottom: 18px;">
                            Setelah akun Anda dihapus, seluruh sumber daya dan data di dalamnya akan dihapus secara permanen.
                        </p>

                        <form method="post" action="{{ route('profile.destroy') }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="btn btn-delete">Hapus Akun</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>