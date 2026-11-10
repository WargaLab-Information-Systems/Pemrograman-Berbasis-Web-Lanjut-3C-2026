<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Profil selalu milik akun yang sedang login ($request->user()); tidak ada parameter
 * ID di URL, sehingga pengguna tidak dapat melihat/mengubah profil orang lain.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
            'anggota' => $request->user()->ensureAnggota(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $user->fill(['name' => $validated['name'], 'email' => $validated['email']]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $dataAnggota = ['nama' => $user->name, 'email' => $user->email];

        // Nomor telepon hanya diubah bila dikirim (kosong => "-").
        if (array_key_exists('no_telepon', $validated)) {
            $dataAnggota['no_telepon'] = $validated['no_telepon'] ?: '-';
        }

        $user->ensureAnggota()->update($dataAnggota);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
