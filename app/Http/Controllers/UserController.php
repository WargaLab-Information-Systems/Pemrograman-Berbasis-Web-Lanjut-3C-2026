<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    private const ROLES = [User::ROLE_ADMIN, User::ROLE_PETUGAS, User::ROLE_SISWA];

    public function index(Request $request)
    {
        $users = User::with('roles')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where(fn ($w) => $w->where('name', 'like', "%{$cari}%")
                    ->orWhere('email', 'like', "%{$cari}%"));
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create', ['roles' => self::ROLES]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['nullable', Rule::in(self::ROLES)],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $role = $request->user()->can('kelola role pengguna')
            ? ($data['role'] ?? User::ROLE_SISWA)
            : User::ROLE_SISWA;

        $user->syncRoles([$role]);
        $user->ensureAnggota();

        return redirect()->route('users.index')->with('success', "Pengguna {$user->name} berhasil dibuat.");
    }

    public function edit(User $user)
    {
        return view('users.edit', ['user' => $user->load('roles'), 'roles' => self::ROLES]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->fill(['name' => $data['name'], 'email' => $data['email']]);

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        $user->save();
        $user->anggota?->update(['nama' => $user->name, 'email' => $user->email]);

        return redirect()->route('users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(self::ROLES)],
        ]);

        if ($user->is($request->user()) && $data['role'] !== User::ROLE_ADMIN) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        if ($user->hasRole(User::ROLE_ADMIN) && $data['role'] !== User::ROLE_ADMIN && $this->adminTerakhir()) {
            return back()->with('error', 'Tidak dapat mengubah role admin terakhir.');
        }

        $user->syncRoles([$data['role']]);

        return redirect()->route('users.index')->with('success', "Role {$user->name} diubah menjadi {$data['role']}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        if ($user->hasRole(User::ROLE_ADMIN) && $this->adminTerakhir()) {
            return back()->with('error', 'Tidak dapat menghapus admin terakhir.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    private function adminTerakhir(): bool
    {
        return User::role(User::ROLE_ADMIN)->count() <= 1;
    }
}
