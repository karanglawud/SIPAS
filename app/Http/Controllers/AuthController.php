<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isAdmin()) {
                return redirect()->route('admin.loans.index')->with('success', 'Selamat datang, ' . Auth::user()->name . '! Anda masuk sebagai Admin PJ.');
            }

            return redirect()->route('catalog.index')->with('success', 'Selamat datang kembali, ' . Auth::user()->name . ' (' . ucfirst(Auth::user()->role) . ')!');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:siswa,guru',
            'identifier' => 'required|string|max:50',
            'class_or_dept' => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'identifier' => $validated['identifier'],
            'class_or_dept' => $validated['class_or_dept'],
            'phone' => $validated['phone'],
            'category_scope' => null,
        ]);

        Auth::login($user);

        return redirect()->route('catalog.index')->with('success', 'Pendaftaran berhasil! Akun Anda telah aktif sebagai ' . ucfirst($user->role) . '. Silakan pilih alat yang ingin dipinjam.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah keluar dari sistem.');
    }
}
