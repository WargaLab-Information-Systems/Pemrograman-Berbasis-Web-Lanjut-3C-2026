<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna yang sedang login.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }


    /**
     * Memperbarui profil pengguna yang sedang login.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                'unique:users,email,' . $user->id,
            ],
        ]);

        $user->fill($validated);

        /*
        |--------------------------------------------------------------------------
        | Reset Verifikasi Email Jika Email Berubah
        |--------------------------------------------------------------------------
        */

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        /*
        |--------------------------------------------------------------------------
        | Sinkronkan Data Anggota
        |--------------------------------------------------------------------------
        |
        | Jika pengguna memiliki data anggota, nama dan email pada data anggota
        | ikut diperbarui agar tetap sama dengan data akun pengguna.
        |
        */

        if ($user->anggota) {
            $user->anggota->update([
                'nama' => $user->name,
                'email' => $user->email,
            ]);
        }

        return Redirect::route('profile.edit')
            ->with('status', 'profile-updated');
    }
}