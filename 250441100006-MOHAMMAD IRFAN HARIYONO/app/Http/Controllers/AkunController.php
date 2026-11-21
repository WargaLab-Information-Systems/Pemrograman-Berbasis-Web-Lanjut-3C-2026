<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User; // <-- INI YANG BIKIN ERROR UNDEFINED SEBELUMNYA
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    // 1. Menampilkan Halaman Tabel
    public function index()
    {
        $users = User::with('roles')->get()->sortBy(function($user) {
            return $user->roles->first()->name ?? 'z'; // 'z' agar yang tidak punya role ditaruh paling bawah
        });

        return view('akun.index', compact('users'));
    }

    // 2. Menyimpan Pengguna Baru (Fungsi Tambah)
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,petugas,siswa'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password dienkripsi
        ]);

        // Memberikan role sesuai pilihan di form
        $user->assignRole($request->role);

        return back()->with('success', 'Akun pengguna baru berhasil ditambahkan!');
    }

    // 3. Mengubah Data & Role Pengguna (Fungsi Ubah)
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            // Email harus unik, KECUALI email milik user yang sedang diedit ini
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,petugas,siswa'
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;

        // Jika form password diisi, maka update password-nya
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        // Ganti role lama dengan role yang baru dipilih
        $user->syncRoles([$request->role]);

        return back()->with('success', 'Data dan Role pengguna berhasil diperbarui!');
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if (Auth::id() !== $user->id) {
            $user->delete();
            return back()->with('success', 'Akun pengguna berhasil dihapus!');
        }
        return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
    }
}