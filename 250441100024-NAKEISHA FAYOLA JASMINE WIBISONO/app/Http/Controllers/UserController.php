<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Anggotas;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/** Pengelolaan akun dan role pengguna (khusus admin). */
class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles')->orderBy('name')->paginate(15);
        $roles = $this->namaRole();

        return view('pengguna.index', compact('users', 'roles'));
    }

    public function create(): View
    {
        return view('pengguna.form', ['user' => new User(), 'roles' => $this->namaRole()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'role' => ['nullable', Rule::in($this->namaRole())],
        ]);

        // Role hanya diterapkan kalau yang membuat punya permission "kelola-role-pengguna"
        $role = ($request->user()->can('kelola-role-pengguna') && ! empty($data['role']))
            ? $data['role']
            : 'siswa';

        DB::transaction(function () use ($data, $role) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->syncRoles([$role]);

            if ($role === 'siswa') {
                Anggotas::pastikan($user, $data['no_telepon'] ?? null, true);
            }
        });

        return redirect()->route('pengguna.index')->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('pengguna.form', ['user' => $user, 'roles' => $this->namaRole()]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (! empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return redirect()->route('pengguna.index')->with('sukses', 'Data pengguna berhasil diperbarui.');
    }

    /** Penetapan role (butuh permission "kelola-role-pengguna" di level route). */
    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'role' => ['required', Rule::in($this->namaRole())],
        ]);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun sendiri.');
        }

        DB::transaction(function () use ($user, $data) {
            $user->syncRoles([$data['role']]);

            if ($data['role'] === 'siswa') {
                Anggotas::pastikan($user, null, true);
            }
        });

        return back()->with('sukses', "Role {$user->name} diubah menjadi {$data['role']}.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user->delete();

        return redirect()->route('pengguna.index')->with('sukses', 'Pengguna berhasil dihapus.');
    }

    private function namaRole(): array
    {
        return Role::orderBy('name')->pluck('name')->all();
    }
}
