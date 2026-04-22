<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminOnly
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Silahkan login terlebih dahulu.');
        }

        // 2. Ambil role user saat ini
        $userRole = Auth::user()->role;

        // 3. Cek apakah role user ada di dalam daftar $roles yang dikirim dari route
        // Jika di route ditulis ['admin:0,1'], maka $roles berisi [0, 1]
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // Jika tidak punya akses, lempar ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
    }
}