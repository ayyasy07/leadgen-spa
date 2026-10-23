<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoggedIn
{
    public function handle(Request $request, Closure $next)
    {
        // Admin area — cek guard admin
        if ($request->is('admin') || $request->is('admin/*')) {
            if (Auth::guard('admin')->check()) {
                return redirect()->route('admin.dashboard.index');
            }
            return $next($request);
        }

        // Frontend area — cek guard web
        if (Auth::guard('web')->check()) {
            return redirect()->route('home');
        }

        return $next($request);
    }
}