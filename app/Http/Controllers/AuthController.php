<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function indexlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|min:3',
            'password' => 'required',
        ]);

        if (Auth::attempt($data)) {
            $user = User::where("username", $request->username)->first();
            // dd($user->role);
            return redirect('/login')->with('success', 'Berhasil login sebagai ' . $user->role);
        }

        return redirect('/login')->with('failed', 'gagal login');
    }
}
