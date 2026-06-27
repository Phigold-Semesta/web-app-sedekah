<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Jika yang login adalah donatur, arahkan ke dashboard donatur
                if ($guard === 'donatur') {
                    return redirect()->route('donatur.dashboard');
                }
                // Jika admin/direktur (guard web), arahkan ke admin dashboard
                return redirect()->route('admin.dashboard');
            }
        }

        return $next($request);
    }
}