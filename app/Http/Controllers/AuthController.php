<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('admin.login');
    }

    // Proses login
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {

            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role == 'owner') {

                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang Owner.');

            }

            if ($user->role == 'manager') {

                return redirect()->route('admin.dashboard')
                    ->with('success', 'Selamat datang Manager.');

            }

            if ($user->role == 'cashier') {

                return redirect()->route('admin.orders.index')
                    ->with('success', 'Selamat datang Cashier.');

            }

            if ($user->role == 'kitchen') {

                return redirect()->route('admin.kitchen.index')
                    ->with('success', 'Selamat datang Kitchen.');

            }

            // jika role tidak dikenali
            Auth::logout();

            return back()->withErrors([
                'email' => 'Role user tidak valid.'
            ]);
        }

        return back()->withErrors([
            'email' => 'Email atau Password salah.',
        ])->onlyInput('email');
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}