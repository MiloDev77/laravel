<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function indexlogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('companies.index');
        }
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'passphrase' => 'required|string'
        ]);

        $credentials = [
            'username' => 'admin',
            'passphrase' => $request->passphrase,
        ];

        if (!Auth::attempt($credentials)) {
            return back()->with('error', 'Invalid Passphrase');
        }

        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            return back()->with('error', 'Access Denied');
        }

        $request->session()->regenerate();
        return redirect()->route('companies.index');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
