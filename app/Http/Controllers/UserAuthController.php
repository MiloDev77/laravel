<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class UserAuthController extends Controller
{
    public function showregister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            // 'username' => 'required|min:6|max:100|unique:users',
            'username' => [
                'required',
                'min:6',
                'max:100',
                'unique:users',
                'regex:/^[a-zA-Z0-9]+$/'
            ],
            'password' => 'required|min:6|max:255',
            // 'avatar_path' => 'image|mimes:jpg,png,jpeg,webp',
            'avatar_path' => 'nullable|image|mimes:jpg,png,jpeg,webp',
        ], [
            'username.regex' => 'Username must consist of numbers and letters.'
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar_path')) {
            $avatarPath = $request->file('avatar_path')->store('avatars', 'public');
        }

        $user = User::Create([
            ...$data,
            'password' => Hash::make($data['password']),
            'avatar_path' => $avatarPath,
            // 'avatar_path' => $data['avatar_path'] ?? $request->file('avatar_path')->store('avatars_path', 'public'),
        ]);

        $user = Auth::login($user);
        return redirect()->route('public.products')->with('success', 'Registration success');
    }

    public function showlogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'username' => 'required|string|min:6|max:100',
            'password' => 'required|string|min:6',
        ]);

        if (!Auth::attempt($data)) {
            return back()->with('error', 'Username or password wrong');
        }

        if (!Auth::user()->role === 'admin') {
            Auth::logout();
            return back()->with('error', 'Only user could access');
        }

        $request->session()->regenerate();
        return redirect()->route('public.products');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('public.products');
    }

    public function editprofile()
    {
        // dd(Auth::user);
        return view('auth.profile', ['user' => Auth::user()]);
    }

    public function updateprofile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            // 'username' => 'required|min:6|max:100|unique:users',
            'username' => [
                'required',
                'min:6',
                'max:100',
                'unique:users',
                'regex:/^[a-zA-Z0-9]+$/'
            ],
            'avatar_path' => 'nullable|image|mimes:jpg,png,jpeg,webp',
        ]);

        $avatar = null;
        if ($request->hasFile('avatar_path')) {
            if ($user->avatar_path) {
                Storage::disk('public')->delete($user->avatar_path);
            }
            $avatar = $request->file('avatar_path')->store('avatars', 'public');
        }

        if ($request->boolean('remove_avatar') && $user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
            $avatar = null;
        }

        if ($user->avatar_path) {
            $avatar = $user->avatar_path;
        }

        // User::where('id', $user->id)->update([
        //     ...$data,
        //     'avatar_path' => $avatar,
        // ]);

        $user->update([
            ...$data,
            'avatar_path' => $avatar,
        ]);

        return back()->with('success', 'Avatar has been changed.');
    }
}
