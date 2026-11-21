<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Pastikan baris ini ada
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    // 1. Tampilkan Daftar Pengguna
    public function index()
    {
        $users = User::with('roles')->latest()->get();
        $roles = Role::all();

        return view('admin.users', compact('users', 'roles'));
    }

    // 2. Simpan Pengguna Baru (Fix Error Call to Undefined Method store)
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role'     => 'required|string|exists:roles,name',
        ]);

        // Buat Akun User Baru
        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Assign Role (Spatie Permission)
        $user->assignRole($request->role);

        return redirect()->back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    // 3. Update Role Pengguna
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|string|exists:roles,name',
        ]);

        $user->syncRoles([$request->role]);

        return redirect()->back()->with('success', 'Role pengguna berhasil diperbarui!');
    }

    // 4. Hapus Pengguna
    public function destroy(User $user)
    {
        // Cek apakah ID user yang dihapus sama dengan ID user yang sedang login
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Pengguna berhasil dihapus!');
    }
}