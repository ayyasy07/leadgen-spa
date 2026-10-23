<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function loginView(Request $request)
    {
        // Regenerate CSRF token tiap buka form login
        // Biar kalo session lama expired, ga 419
        $request->session()->regenerateToken();

        return view('login.main', [
            'layout' => 'login',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        // Pake guard 'admin'
        if (!Auth::guard('admin')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Email atau password salah.');
        }

        $request->session()->regenerate();

        $user = Auth::guard('admin')->user();

        if (!$user->isAdmin()) {
            Auth::guard('admin')->logout();
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Akun ini bukan admin.');
        }

        return redirect()
            ->route('admin.dashboard.index')
            ->with('success', 'Selamat datang, ' . $user->name . '!');
    }

    public function logout(Request $request)
    {
        // Logout cuma guard admin (FE tetep login — beda pintu)
        Auth::guard('admin')->logout();

        // Regenerate session → CSRF token baru, biar ga 419
        $request->session()->regenerate();

        return redirect()
            ->route('admin.login')
            ->with('success', 'Anda telah logout.');
    }
}