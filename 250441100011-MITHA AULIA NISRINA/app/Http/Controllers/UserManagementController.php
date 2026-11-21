<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    /**
     * Menampilkan daftar pengguna.
     */
    public function index(): View
    {
        $users = User::with('roles')
            ->orderBy('name')
            ->get();

        $roles = Role::whereIn('name', [
            'admin',
            'petugas',
            'siswa',
        ])
            ->orderBy('name')
            ->get();

        return view('admin.users.index', compact(
            'users',
            'roles'
        ));
    }

    /**
     * Mengubah role pengguna.
     */
    public function updateRole(
        Request $request,
        User $user
    ): RedirectResponse {
        $validated = $request->validate([
            'role' => [
                'required',
                'string',
                'in:admin,petugas,siswa',
            ],
        ]);

        $user->syncRoles([
            $validated['role'],
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with(
                'success',
                'Role pengguna berhasil diperbarui.'
            );
    }
}