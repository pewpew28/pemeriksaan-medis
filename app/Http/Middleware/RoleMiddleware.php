<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth; // Penting: import Auth

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Pastikan pengguna sudah login
        if (!Auth::check()) {
            return redirect('login'); // Arahkan ke halaman login jika belum autentikasi
        }

        $user = Auth::user();

        // 2. Periksa apakah pengguna memiliki salah satu peran yang diizinkan
        // Metode hasAnyRole() disediakan oleh Spatie Laravel Permission
        if (!$user->hasAnyRole($roles)) {
            // Jika tidak memiliki peran yang diizinkan, tolak akses
            abort(403, 'Anda tidak memiliki izin untuk mengakses halaman ini.');
        }

        // 3. Lanjutkan request jika pengguna memiliki peran yang sesuai
        return $next($request);
    }
}