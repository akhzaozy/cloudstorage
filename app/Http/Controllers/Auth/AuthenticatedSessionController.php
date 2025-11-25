<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $res = Http::withHeaders([
            'apikey' => env('SUPABASE_KEY'),
            'Content-Type' => 'application/json',
        ])->post(env('SUPABASE_URL') . '/auth/v1/token?grant_type=password', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($res->failed()) {
            return back()->withErrors(['email' => 'Email atau password salah']);
        }

        $data = $res->json();
        session(['supabase_user' => $data['user'], 'supabase_token' => $data['access_token']]);

        return redirect('/drive'); // FIX DI SINI
    }

    public function destroy(Request $request)
    {
        session()->flush();
        return redirect('/login');
    }
}
