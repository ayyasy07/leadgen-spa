<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    /**
     * Tampilkan form profile.
     */
    public function edit()
    {
        return view('user.pages.profile');
    }

    /**
     * Update profile.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => [
                'required', 'string', 'email', 'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'phone'    => 'nullable|string|max:30',
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ];

        // Update password kalo diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('profile.index')
            ->with('success', 'Profile updated successfully!');
    }
}