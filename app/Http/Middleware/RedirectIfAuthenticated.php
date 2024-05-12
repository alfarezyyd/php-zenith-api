<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $guard = null): mixed
    {
        if (Auth::guard($guard)->check()) {
            // Jika pengguna sudah terautentikasi, redirect ke halaman lain
            return redirect()->to(env('NEXT_WEB_CLIENT_URL')); // Ganti '/dashboard' dengan route yang sesuai
        }

        // Jika belum terautentikasi, lanjutkan ke route berikutnya
        return $next($request);
    }
}
