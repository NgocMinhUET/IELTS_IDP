<?php

namespace App\Http\Controllers\CMS;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends CMSController
{
    public function login()
    {
        return view('auth.sign_in');
    }

    public function authenticate(Request $request): \Illuminate\Http\RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // apply is active account condition for query
        $credentials[] = fn (Builder $query) => $query->isActive();

        if (Auth::attempt($credentials, $request->has('remember'))) {

            $request->session()->regenerate();

            return redirect()->intended(Auth::user()->role->redirectCMSRoute());
        }


        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request): \Illuminate\Http\RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}