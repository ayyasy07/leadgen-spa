<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        // Default guard = web (user)
        $guards = empty($guards) ? ['web'] : $guards;

        // Kalo di /admin/* — cek guard admin
        if ($request->is('admin') || $request->is('admin/*')) {
            $guards = ['admin'];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Guard admin → admin dashboard
                if ($guard === 'admin') {
                    return redirect()->route('admin.dashboard.index');
                }

                // Guard web (user) → home
                return redirect()->route('home');
            }
        }

        return $next($request);
    }
}