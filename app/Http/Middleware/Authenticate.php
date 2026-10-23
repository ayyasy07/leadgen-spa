<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Auth;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {

            // Kalo request dari /admin/* — pake guard admin
            if ($request->is('admin') || $request->is('admin/*')) {
                return route('admin.login');
            }

            return route('login');
        }
    }

    /**
     * Override: cek guard default berdasarkan URL.
     */
    protected function authenticate($request, array $guards)
    {
        // Kalo di /admin/* — pake guard admin
        if ($request->is('admin') || $request->is('admin/*')) {
            $guards = ['admin'];
        }

        // Kalo kosong, fallback ke default
        if (empty($guards)) {
            $guards = [null];
        }

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::shouldUse($guard);
            }
        }

        $this->unauthenticated($request, $guards);
    }
}