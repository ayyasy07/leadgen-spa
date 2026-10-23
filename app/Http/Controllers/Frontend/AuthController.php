<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function loginView()
    {
        return view('user.pages.login');
    }

    public function registerView()
    {
        return view('user.pages.register');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $remember = $request->has('remember');

        // Pake guard 'web' (default)
        if (!Auth::guard('web')->attempt($credentials, $remember)) {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Invalid email or password.');
        }

        $request->session()->regenerate();

        // HAPUS HISTORY CHAT GUEST KARENA SEKARANG UDAH LOGIN
        session()->forget('chatbot_history_guest');

        $user = Auth::guard('web')->user();

        if ($user->isAdmin()) {
            Auth::guard('web')->logout();
            return redirect()
                ->route('admin.login')
                ->with('error', 'Please login through the admin page.');
        }

        return redirect()
            ->route('home')
            ->with('success', 'Welcome back, ' . $user->name . '!');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'agree'    => 'accepted',
        ], [
            'agree.accepted'     => 'You must agree to the Terms & Conditions.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min'       => 'Password must be at least 8 characters.',
            'email.unique'       => 'Email is already registered.',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'password' => Hash::make($request->password),
            'role'     => 'customer',
        ]);

        return redirect()
            ->route('login')
            ->with('success', 'Registration successful! Please log in.');
    }

    public function logout(Request $request)
    {
        // HAPUS HISTORY CHAT USER SEBELUM LOGOUT
        if (Auth::check()) {
            session()->forget('chatbot_history_user_' . Auth::id());
        }

        Auth::guard('web')->logout();

        $request->session()->forget('login_web_' . sha1(\Illuminate\Auth\SessionGuard::class));

        return redirect()
            ->route('home')
            ->with('success', 'You have been logged out.');
    }
}