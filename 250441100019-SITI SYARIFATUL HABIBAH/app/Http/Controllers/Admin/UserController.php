<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

/**
 * Kelola akun pengguna (permission: kelola data pengguna).
 * Penetapan role hanya untuk yang punya permission: kelola role pengguna.
 */
class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::with('roles')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->query('cari');
                $q->where(function ($w) use ($cari) {
                    $w->where('name', 'like', "%{$cari}%")
                      ->orWhere('email', 'like', "%{$cari}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.pengguna.index', compact('users'));
    }

    public function create(): View
    {
        return view('admin.pengguna.form', [
            'user'  => new User(),
            'roles' => $this->daftarRole(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'no_telepon' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+]+$/'],
            'password'   => ['required', 'confirmed', Password::defaults()],
            'role'       => ['nullable', Rule::in($this->daftarRole())],
        ]);

        DB::transaction(function () use ($request, $data) {
            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            // Role hanya boleh ditentukan oleh yang berwenang; selain itu otomatis 'siswa'
            $role = ($request->user()->can('kelola role pengguna') && ! empty($data['role']))
                ? $data['role']
                : 'siswa';
            $user->assignRole($role);

            $user->anggota()->create([
                'nama'       => $data['name'],
                'email'      => $data['email'],
                'no_telepon' => $data['no_telepon'],
            ]);
        });

        return redirect()->route('admin.pengguna.index')->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        return view('admin.pengguna.form', [
            'user'  => $user->load('anggota', 'roles'),
            'roles' => $this->daftarRole(),
        ]);
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate([
            'name'       => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'no_telepon' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[0-9+]+$/'],
            'password'   => ['nullable', 'confirmed', Password::defaults()],
            'role'       => ['nullable', Rule::in($this->daftarRole())],
        ]);

        $bolehUbahRole = $request->user()->can('kelola role pengguna') && ! empty($data['role']);

        if ($bolehUbahRole && $user->is($request->user()) && ! $user->hasRole($data['role'])) {
            return back()->withInput()->withErrors(['role' => 'Anda tidak dapat mengubah role akun Anda sendiri.']);
        }

        DB::transaction(function () use ($user, $data, $bolehUbahRole) {
            $user->name  = $data['name'];
            $user->email = $data['email'];

            if (! empty($data['password'])) {
                $user->password = Hash::make($data['password']);
            }
            $user->save();

            if ($bolehUbahRole) {
                $user->syncRoles([$data['role']]);
            }

            $user->anggota()->updateOrCreate([], [
                'nama'       => $data['name'],
                'email'      => $data['email'],
                'no_telepon' => $data['no_telepon'],
            ]);
        });

        return redirect()->route('admin.pengguna.index')->with('sukses', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($user->is($request->user())) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('admin.pengguna.index')->with('sukses', 'Pengguna berhasil dihapus.');
    }

    private function daftarRole(): array
    {
        return Role::orderBy('name')->pluck('name')->all();
    }
}
