<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function login(): object
    {
        if (!Auth::check()) {
            return Inertia::render('auth/Login');
        } else {
            return redirect()->intended();
        }
    }

    public function authenticate(Request $request): object
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended();
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function destroy(): object
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
