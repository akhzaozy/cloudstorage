<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Illuminate\Validation\ValidationException;

class AuthenticatedSessionController extends Controller
{
    /**
     * Tampilkan halaman login.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Proses login ke MariaDB Lokal.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba login via MariaDB (Eloquent Auth)
        // 'remember' kita set false biar simpel dulu
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            // Jika berhasil, buat ulang session biar aman
            $request->session()->regenerate();

            // Redirect ke halaman drive/dashboard
            return redirect()->intended('/drive');
        }

        // 3. Jika gagal, lempar error balik ke form login
        throw ValidationException::withMessages([
            'email' => ['Email atau password yang kamu masukkan salah.'],
        ]);
    }

    /**
     * Logout dari sistem.
     */
    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
