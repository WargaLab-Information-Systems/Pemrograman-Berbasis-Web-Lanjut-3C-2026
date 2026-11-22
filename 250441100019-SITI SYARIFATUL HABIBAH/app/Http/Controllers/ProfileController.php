<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

/**
 * Profil MILIK SENDIRI. Semua data diambil dari $request->user() (akun yang login),
 * tidak ada parameter id, sehingga tidak mungkin melihat atau mengubah profil orang lain.
 */
class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user()->load('anggota'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->safe()->only(['name', 'email']));

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $user->anggota()->updateOrCreate([], [
            'nama'       => $user->name,
            'email'      => $user->email,
            'no_telepon' => $request->validated('no_telepon'),
        ]);

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
}
