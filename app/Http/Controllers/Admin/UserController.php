<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('phone', 'like', "%{$s}%");
            });
        }

        $users = $query->orderByDesc('created_at')->get();

        return view('pages.users-crud.index', [
            'users'        => $users,
            'filterRole'   => $request->get('role', 'all'),
            'filterSearch' => $request->get('search', ''),
            'layout'       => 'side-menu',
        ]);
    }

    public function create()
    {
        return view('pages.users-crud.create', [
            'layout' => 'side-menu',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:admin,customer',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'phone'    => $request->phone,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User created.');
    }

    /**
     * Show user + riwayat booking.
     */
    public function show(User $user)
    {
        // Ambil riwayat booking user
        $bookings = Booking::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->get();

        // Revenue statuses — konsisten sama Dashboard
        $revenueStatuses = [
            Booking::STATUS_PAID,
            Booking::STATUS_COMPLETED,
            Booking::STATUS_NO_SHOW,
            Booking::STATUS_CANCELLED,
        ];

        $bookingStats = [
            'total'     => $bookings->count(),
            'pending'   => $bookings->where('status', Booking::STATUS_PENDING)->count(),
            'paid'      => $bookings->where('status', Booking::STATUS_PAID)->count(),
            'completed' => $bookings->where('status', Booking::STATUS_COMPLETED)->count(),
            'cancelled' => $bookings->where('status', Booking::STATUS_CANCELLED)->count(),
            'no-show'   => $bookings->where('status', Booking::STATUS_NO_SHOW)->count(),
            'revenue'   => $bookings->whereIn('status', $revenueStatuses)->sum('net_revenue'),
        ];

        return view('pages.users-crud.show', [
            'user'         => $user,
            'bookings'     => $bookings,
            'bookingStats' => $bookingStats,
            'layout'       => 'side-menu',
        ]);
    }

    public function edit(User $user)
    {
        return view('pages.users-crud.edit', [
            'user'   => $user,
            'layout' => 'side-menu',
        ]);
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone'    => 'nullable|string|max:30',
            'role'     => 'required|in:admin,customer',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name'  => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role'  => $request->role,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User updated.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User deleted.');
    }
}