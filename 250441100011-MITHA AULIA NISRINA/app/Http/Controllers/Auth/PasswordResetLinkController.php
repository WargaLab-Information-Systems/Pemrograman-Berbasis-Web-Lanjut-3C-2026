<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Menampilkan halaman lupa kata sandi.
     */
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Memproses permintaan atur ulang kata sandi.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'email' => __('We can\'t find a user with that email address.'),
            ]);
        }

        $token = Password::broker()->createToken($user);

        return redirect()->route('password.reset', [
            'token' => $token,
            'email' => $user->email,
        ]);
    }
}