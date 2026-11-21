<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form profil pengguna.
     */
    public function edit(Request $request): View
    {
        // 1. Pengecekan Permission Spatie
        if (!$request->user()->can('Lihat profil sendiri')) {
            abort(403, 'Anda tidak memiliki hak akses untuk melihat profil.');
        }

        // 2. Ownership Check: Selalu mengambil akun yang sedang login (bukan dari ID URL)
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // 1. Pengecekan Permission Spatie
        if (!$request->user()->can('Ubah profil sendiri')) {
            abort(403, 'Anda tidak memiliki hak akses untuk mengubah profil.');
        }

        // 2. Ownership Check: Hanya mengubah akun yang sedang login
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Menghapus akun pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}